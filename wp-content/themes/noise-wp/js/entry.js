var Noise = Noise || {};

(function ( $ )
{
	$.browser.chrome = /chrome/.test( navigator.userAgent.toLowerCase() );

	// Video, Audio
	var audioOptions = {
		features         : ['playpause', 'progress', 'current', 'duration', 'volume'],
		pauseOtherPlayers: false
	};
	var videoOptions = {
		features          : ['playpause', 'progress', 'current', 'duration', 'volume'],
		alwaysShowControls: true,
		enableAutosize    : false,
		pauseOtherPlayers : false
	};

	if ( typeof _wpmejsSettings !== 'undefined' )
		audioOptions.pluginPath = _wpmejsSettings.pluginPath;

	function initAudioPlayer()
	{
		$( 'audio' ).not( '.wp-audio-shortcode' ).each( function ()
		{
			var $spectrum = $( this ).prev( 'canvas' );
			if ( $spectrum.length )
				Noise.Spectrum.init( {
					canvas: $spectrum.attr( 'id' ),
					audio : $( this ).attr( 'id' ),
					fill  : '#495745'
				} );
			$( this ).mediaelementplayer( audioOptions );
		} );
	}

	function initVideoPlayer()
	{
		$( 'video' ).not( '.wp-video-shortcode' ).mediaelementplayer( videoOptions );
	}

	function initSlider()
	{
		var sliderOpts = {
			animation    : "slide",
			animationLoop: false,
			easing       : 'easeInOutExpo',
			directionNav : false,
			controlNav   : true,
			slideshow    : true
		};

		if ( $.browser.safari || $.browser.chrome )
			sliderOpts.useCSS = false;

		$( '.flexslider' ).flexslider( sliderOpts );
	}

	function restartSlider()
	{
		var $slider = $( '.flexslider' );
		if ( $slider.length )
			$slider.data( 'flexslider' ).resize();
	}

	$( function ()
	{
		initAudioPlayer();

		initVideoPlayer();

		initSlider();

		// Ajax pagination
		$( '#content' ).on( 'click', '.ajax-navigation a', function ( event )
		{
			event.preventDefault();
			var $this = $( this ),
				url = $this.attr( 'href' ),
				height = $( document ).height();

			$this.addClass( 'loading' );
			$.get( url, function ( data )
			{
				var content = $( data ).find( '#content' ).html(),
					title = $( data ).find( 'title' ).text();

				$this.parents( '.ajax-navigation' ).remove();
				$( content ).hide().appendTo( '#content' );
				$( '#content' ).find( 'article:hidden, .pagination' ).slideDown( 1000 );
				setTimeout( function ()
				{
					initAudioPlayer();
					initVideoPlayer();
					initSlider();
					window.history.pushState( null, title, url );

					// Change map position after document height changed
//					if ( typeof Noise.map != 'undefined' && typeof google != 'undefined' )
//					{
//						var offset = $( document ).height() - height;
//						Noise.map.panBy( 0, -(offset / 3) );
//					}
				}, 1100 );
			} );
		} );

		// Minimize sidebar
		var sidebarWidth = $( '.sidebar' ).width(),
			contentWidth = $( '.content' ).width();
		$( '.minimize-sidebar' ).on( 'click', function ( event )
		{
			event.preventDefault();

			var $this = $( this ),
				$sidebar = $this.siblings( '.sidebar' ),
				$content = $this.siblings( '.content' );

			if ( $this.hasClass( 'minimized' ) )
			{
				$sidebar.stop( true, true ).animate( { width: sidebarWidth }, 500, function ()
				{
					$sidebar.children().animate( { opacity: 1 } );
				} );
				$content.stop( true, true ).animate( { width: contentWidth }, 300, function ()
				{
					restartSlider();
				} );
			}
			else
			{
				$sidebar.children().css( 'opacity', 0 );
				$sidebar.stop( true, true ).animate( { width: 0 }, 300 );
				$content.stop( true, true ).animate( { width: '100%' }, 500, function ()
				{
					restartSlider();
				} );
			}

			$this.toggleClass( 'minimized' );
		} );

		// Play track from featured tracks widget
		$( '.widget-featured-tracks' ).on( 'click', '.play-track', function( event )
		{
			event.preventDefault();
			var $this  = $( this ),
				$track = $this.parents( '.track' ),
				$thumb = $track.find( '.track-image' );

			if ( $thumb.length )
			{
				var $play = $thumb.find( '.play-track' );
				if ( $play.hasClass( 'entypo-pause' ) )
				{
					$play.removeClass( 'entypo-pause' );
				}
				else
				{
					$( '.play-track', '.widget-featured-tracks' ).removeClass( 'entypo-pause' );
					$play.addClass( 'entypo-pause' );
				}
			}
			else
			{
				if ( $this.hasClass( 'entypo-play' ) )
				{
					$this.removeClass( 'entypo-play' ).addClass( 'entypo-pause' );
				}
				else
				{
					$( '.play-track', '.widget-featured-tracks' ).removeClass( 'entypo-play entypo-pause' );
					$this.addClass( 'entypo-play' );
				}
			}

			$track.find( '.mejs-playpause-button' ).trigger( 'click' );
		} );
	} );
})( jQuery );

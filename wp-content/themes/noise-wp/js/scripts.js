/**
 * Easing
 */
// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	def          : 'easeInOutExpo',
	easeInOutExpo: function ( x, t, b, c, d )
	{
		if ( t == 0 ) return b;
		if ( t == d ) return b + c;
		if ( (t /= d / 2) < 1 ) return c / 2 * Math.pow( 2, 10 * (t - 1) ) + b;
		return c / 2 * (-Math.pow( 2, -10 * --t ) + 2) + b;
	}
} );

/**
 * jQuery Slide Unlock
 *
 * Author: FitWP
 */
(function ( $ )
{
	$.fn.slideUnlock = function ( options )
	{
		if ( !$.fn.udraggable )
			return;

		// Settings
		var settings = $.extend(
			{
				handle  : '.handle',
				unlock  : 0,
				unlocked: function ()
				{
				}
			},
			options );

		var $this = $( this );

		// Unlock
		$( settings.handle, this ).udraggable( {
			axis       : 'x',
			containment: [0, 0, $( this ).parent().width(), 0],
			drag       : function ( event, ui )
			{
				if ( ui.position.left >= settings.unlock )
				{
					$this.fadeOut();
					$( 'body' ).removeClass( 'opener' );
					settings.unlocked();
				}
			},
			stop       : function ( event, ui )
			{
				if ( ui.position.left < settings.unlock )
				{
					$( this ).animate( {
						left: 0
					} );
				}
			}
		} );
	}
})( jQuery );

/**
 * Jquery Tabs Plugin
 * Author: FitWP
 */
(function ( $ )
{
	$.fn.tabs = function ( options )
	{
		var settings = $.extend(
			{
				duration: 1000,
				before  : function ()
				{
				},
				after   : function ()
				{
				}
			},
			options
		);

		return this.each( function ()
		{
			var $this = $( this ),
				$tabs = $this.find( '.tabs-nav a' ),
				$panels = $this.find( '.tab-panel' );

			$this.on( 'click', '.tabs-nav a', function ( event )
			{
				event.preventDefault();
				var $el = $( this );

				if ( $el.hasClass( 'active' ) )
					return;

				var target = $el.attr( 'href' );

				settings.before( this, target );

				$tabs.removeClass( 'active' );
				$el.addClass( 'active' );

				$panels.stop( true, true ).hide().removeClass( 'open' );
				$panels.filter( target ).addClass( 'open' ).stop( true, true ).fadeIn( settings.duration, function ()
				{
					$this.find( '.tab-panel' ).removeAttr( 'style' );
					settings.after( this );
				} );
			} );
		} );
	}
})( jQuery );

/**
 * Countdown
 */
(function ( $ )
{
	$.fn.countdown = function ()
	{
		var $this = $( this ),
			settings = {
				format: 'month day hour minute second',
				end   : $this.data( 'end' )
			},
			endTime = new Date( settings.end );

		if ( endTime <= new Date() )
			return;

		var switchDigit = function ( position, value )
		{
			var $pos = $this.find( '.count-' + position );

			// Return if already showing this value
			if ( value == $pos.text() )
				return;

			value = 10 > value ? '0' + value : '' + value;

			$pos.text( value );
		};

		var tick = function ()
		{
			var now = new Date(),
				difference = Math.floor( ( endTime - now ) / 1000 ),
				days;

			// Update second
			switchDigit( 'second', Math.floor( difference % 60 ) );
			difference = Math.floor( difference / 60 );

			// Update minute
			switchDigit( 'minute', Math.floor( difference % 60 ) );
			difference = Math.floor( difference / 60 );

			// Update hour
			switchDigit( 'hour', Math.floor( difference % 24 ) );
			days = Math.floor( difference / 24 );

			var months = monthsDiff( now, endTime ), monthDays;

			for ( var i = 1; i <= months; i++ )
			{
				monthDays = new Date( now.getFullYear(), now.getMonth() + 2, 0 ).getDate();
				days -= monthDays;
			}

			if ( days < 0 )
			{
				months--;
				days = monthDays + days;
			}

			// Update day
			switchDigit( 'day', days );

			// Update month
			switchDigit( 'month', months );

			if ( 0 >= parseInt( $this.text().replace( /(\D)/gi, '' ) ) )
			{
				clearInterval( counting );
			}
		};

		var counting = setInterval( tick, 1000 );
	};

	function monthsDiff( from, to )
	{
		var months;
		months = (to.getFullYear() - from.getFullYear()) * 12;
		months -= from.getMonth() + 1;
		months += to.getMonth() + 1;
		return months <= 0 ? 0 : months;
	}
})( jQuery );

var Noise = Noise || {};

// Setup global vars
// Will be used in noise.js as well (no need to recalculate layout)
(function ( $ )
{
	Noise.$window = $( window );
	Noise.wWidth = Noise.$window.width();
	Noise.wHeight = Noise.$window.height();
	Noise.$navMenu = $( '#nav-menu' );
	Noise.navHeight = Noise.$navMenu.height();
	Noise.$HtmlBody = $( 'html, body' );
	Noise.$body = $( 'body' );

	Noise.isChrome = /chrome/.test( navigator.userAgent.toLowerCase() );
	Noise.isSafari = /safari/.test( navigator.userAgent.toLowerCase() ) && !Noise.isChrome;
	Noise.useCSSTransition = !( Noise.isSafari || Noise.isChrome );

	window.addEventListener( "orientationchange", function ()
	{
		Noise.wWidth = Noise.$window.width();
		Noise.wHeight = Noise.$window.height();
	}, false );
})( jQuery );

// Paralax plugin
(function ( $ )
{
	$.fn.parallax = function ( xpos, speedFactor )
	{
		xpos = xpos || '50%';
		speedFactor = speedFactor || 0.6;

		return this.each( function ()
		{
			var $this = $( this ),
				height = $this.height(),
				top = $this.offset().top;

			// Called whenever the window is scrolled or resized
			function update()
			{
				var pos = Noise.$window.scrollTop();

				// Check if totally above or totally below viewport
				if ( top + height < pos || top > pos + Noise.wHeight )
					return;

				$this.css( 'backgroundPosition', xpos + ' ' + Math.round( (top - pos) * speedFactor ) + 'px' );
			}

			Noise.$window.on( 'scroll', update );
			update();
		} );
	};
})( jQuery );

(function ( $ )
{
	Noise.initMap = function ()
	{
		var $map = $( '#noise-map' );
		if ( !$map.length )
			return;

		if ( typeof google == 'undefined' )
			return;

		var location = new google.maps.LatLng( $map.data( 'lat' ), $map.data( 'lng' ) ),
			mapOptions = {
				scrollwheel       : false,
				draggable         : !noiseVars.isMobile,
				zoom              : $map.data( 'zoom' ),
				center            : location,
				mapTypeId         : google.maps.MapTypeId.ROADMAP,
				panControl        : false,
				zoomControl       : false,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.SMALL
				},
				scaleControl      : false,
				streetViewControl : false
			};

		var map = new google.maps.Map( document.getElementById( 'noise-map' ), mapOptions );
		var marker = new google.maps.Marker( {
			map     : map,
			position: location,
			icon    : noiseVars.marker
		} );

		if ( noiseVars.contact )
		{
			var infoWindow = new google.maps.InfoWindow( {
				content : '<div class="contact-info-window">' + noiseVars.contact + '</div>',
				maxWidth: 400
			} );

			google.maps.event.addListener( marker, 'click', function ()
			{
				infoWindow.open( map, marker );
			} );
		}
		Noise.map = map;
	};

	Noise.ytResize = function ( element )
	{
		var $player = $( element ),
			$section = $player.parents( 'section' ),
			ratio = 16 / 9,
			width = $section.outerWidth(),
			height = $section.outerHeight(),
			pWidth,
			pHeight;

		if ( width / ratio < height )
		{
			pWidth = Math.ceil( height * ratio );
			$player.width( pWidth ).height( height ).css( {left: (width - pWidth) / 2, top: 0} );
		}
		else
		{
			pHeight = Math.ceil( width / ratio );
			$player.width( width ).height( pHeight ).css( {left: 0, top: (height - pHeight) / 2} );
		}
	};

	// Load Youtube iframe JS API
	var tag = document.createElement( 'script' );
	tag.src = "//www.youtube.com/iframe_api";
	var firstScript = document.getElementsByTagName( 'script' )[0];
	firstScript.parentNode.insertBefore( tag, firstScript );

	// Fire on document ready
	$( function ()
	{
		// Slider fullscreen
		var	$sectionSlider = $( '#section-slider' ),
			$nav           = $( '#nav' ),
			$sliderYT      = $( '#top-youtube-bg' ),
			$cdYT          = $( '#countdown-youtube-bg' ),
			$sliderVB 	   = $( '#video-background' ),
			$toggleSound   = $( '#toggle-bg-sound' );

		// Youtube video background
		if ( $sectionSlider.length )
		{
			$sectionSlider.height( Noise.wHeight );
			Noise.$window.on( 'resize', function ()
			{
				$sectionSlider.height( Noise.wHeight );
			} );

			var $bgSlider = $( '#background-slider' );

			// Top slider
			$sectionSlider.find( '.flexslider' ).flexslider( {
				animation     : 'fade',
				animationSpeed: 1200,
				useCSS        : Noise.useCSSTransition,
				prevText      : '',
				nextText      : '',
			} );

			$bgSlider.flexslider( {
				animation     : 'fade',
				animationSpeed: 1200,
				useCSS        : Noise.useCSSTransition,
				prevText      : '',
				nextText      : '',
				directionNav  : false,

			} );

			// Slider background
			if ( $bgSlider.length )
			{
				Noise.bgSlider = $bgSlider.flexslider( {
					animation     : 'fade',
					slideshow     : !Noise.$body.hasClass( 'page-template-template-onepage-php' ),
					animationSpeed: 1200,
					useCSS        : Noise.useCSSTransition,
					directionNav  : false,
					controlNav    : false
				} );
			}


		}

		if ( $sliderVB.length )
		{
			Noise.sliderVB = new MediaElementPlayer( '#video-background', {
				videoWidth: Noise.wWidth,
				videoHeight: Noise.wHeight,
				loop: true,
				pauseOtherPlayers: false,
				startVolume: 0.8,
				success : function ( mediaElement, domObject )
				{

				}
			} );

			$toggleSound.on( 'click', function( event )
			{
				event.preventDefault();
				var muted = !$toggleSound.hasClass( 'entypo-sound' );
				Noise.sliderVB.setMuted( !muted );
				$toggleSound.toggleClass( 'entypo-sound' ).toggleClass( 'entypo-mute' );
			} );
		}
		else if ( $sliderYT.length || $cdYT.length )
		{
			window.onYouTubeIframeAPIReady = function()
			{
				if ( $sliderYT.length )
				{
					Noise.sliderBgPlayer = new YT.Player( 'top-youtube-bg', {
						width: Noise.wWidth,
						height: Noise.wHeight,
						videoId: $sliderYT.data( 'video' ),
						playerVars: {
							controls: 0,
							showinfo: 0,
							rel     : 0,
							loop    : 1,
							autoplay: 1
						},
						events: {
							onReady: function( e )
							{
								Noise.ytResize( '#top-youtube-bg' );
								if ( !Noise.isSafari )
								{
									e.target.setPlaybackQuality( 'hd720' );
								}
								if ( noiseVars.autoplaysoundvideo )
								{
									e.target.unMute();
								}
								else
								{
									e.target.mute();
								}
							},
							onStateChange: function( state )
							{
								if ( state.data === 0 )
								{
									Noise.sliderBgPlayer.seekTo( 0 );
								}
							}
						}
					} );

					$toggleSound.on( 'click', function( event )
					{
						event.preventDefault();

						if ( Noise.sliderBgPlayer.isMuted() )
						{
							Noise.sliderBgPlayer.unMute();
							$toggleSound.addClass( 'entypo-sound' ).removeClass( 'entypo-mute' );
						}
						else
						{
							Noise.sliderBgPlayer.mute();
							$toggleSound.removeClass( 'entypo-sound' ).addClass( 'entypo-mute' );
						}
					} );
				}

				if ( $cdYT.length )
				{
					var $sectionCountdown = $( '#section-countdown' );
					Noise.cdBgPlayer = new YT.Player( 'countdown-youtube-bg', {
						width: $sectionCountdown.outerWidth(),
						height: $sectionCountdown.outerHeight(),
						videoId: $cdYT.data( 'video' ),
						playerVars: {
							controls: 0,
							showinfo: 0,
							rel     : 0,
							loop    : 1,
							autoplay: 1
						},
						events: {
							onReady: function( e )
							{
								Noise.ytResize( '#countdown-youtube-bg' );
								if ( !Noise.isSafari )
								{
									e.target.setPlaybackQuality( 'hd720' );
								}
								e.target.mute();
							},
							onStateChange: function( state )
							{
								if ( state.data === 0 )
								{
									Noise.cdBgPlayer.seekTo( 0 );
								}
							}
						}
					} );
				}

				Noise.$window.on( 'resize', function()
				{
					if ( $sliderYT.length )
						Noise.ytResize( '#top-youtube-bg' );

					if ( $cdYT.length )
						Noise.ytResize( '#countdown-youtube-bg' );
				} ).trigger( 'resize' );
			}
		}

		// Add class to thin menu item
		$nav.find( '.menu, .sub-menu' ).children( 'li' ).each( function()
		{
			var $this = $( this ),
				right = Noise.wWidth - $this.offset().left - $this.outerWidth();

			if ( right < 190 )
				$( this ).addClass( 'thin-menu-item' );
		} );

		/**
		 * Sticky menu
		 *
		 * @return void
		 */
		var menuTop = $sectionSlider.length ? Noise.wHeight : 0
			$sectionPlayer = $( '#section-player' );
		function stickyMenu()
		{
			// No sticky header for short screens
			if ( Noise.wHeight < 480 )
			{
				Noise.$body.removeClass( 'sticky-nav' );
				$sectionPlayer.attr( 'style', '' );
				return;
			}
			// Height of player: 40px
			if ( Noise.$window.scrollTop() <= menuTop - Noise.navHeight - 40 )
			{
				Noise.$body.removeClass( 'sticky-nav' );
				$sectionPlayer.attr( 'style', '' ).removeClass( 'showdown' );
			}
			else
			{
				Noise.$body.addClass( 'sticky-nav' );
				$sectionPlayer.css( 'top', Noise.navHeight );
				if ( Noise.$body.hasClass( 'track-info-showed' ) )
					$sectionPlayer.addClass( 'showdown' );
			}
		}

		Noise.$window.on( 'scroll', stickyMenu ).trigger( 'scroll' );

		// Nav dropdown for mobile
		var addedNav = false;

		/**
		 * Show dropdown nav for mobile
		 *
		 * @return void
		 */
		function mobileNav()
		{
			if ( addedNav || Noise.$window.width() > 800 )
				return;

			var $select = $( '<select/>' ).insertAfter( $nav ),
				options = '<option value="">' + noiseVars.navDefault + '</option>';
			$nav.find( 'a' ).each( function ()
			{
				var $el = $( this ),
					text = $el.text(),
					selected = '',
					depth = $el.parents( 'ul' ).length;

				for ( var i = 1; i < depth; i++ )
				{
					text = '— ' + text;
				}
				if ( $el.hasClass( 'current-menu-item' ) )
					selected = ' selected="selected"';

				options += '<option value="' + $el.attr( 'href' ) + '"' + selected + '>' + text + '</option>';
			} );
			$select.append( options ).change( function ()
			{
				location.href = $select.find( 'option:selected' ).val();
			} );
			$select.wrap( '<div class="mobile-nav"/>' ).before( '<i class="entypo-list2"></i>' );

			addedNav = true;
		}

		mobileNav();
		Noise.$window.on( 'resize', mobileNav );

		// Preload
		if( noiseVars.preloader )
		{
			Noise.$body.queryLoader2( {
				backgroundColor: noiseVars.bgColor,
				percentage     : true,
				barHeight      : 40,
				minimumTime    : 200,
				onComplete     : function ()
				{
					$( '#load-screen' ).hide();

					if ( !noiseVars.isMobile )
						$( '.parallax' ).parallax();
				}
			} );
		}
		else
		{
			$( '#load-screen' ).hide();

			if ( !noiseVars.isMobile )
				$( '.parallax' ).parallax();
		}

		// Slider to remove opener screen
		var $openerSection = $( '#section-opener' ),
			$unlocker = $openerSection.find( '.unlocker' );
		$openerSection.slideUnlock( {
			unlock  : $unlocker.width() - $unlocker.find( '.handle .arrow' ).width(),
			unlocked: function ()
			{
				// One time unlock
				document.cookie = 'unlock=1';
			}
		} );

		// Init Map
		Noise.initMap();

		// Tabs
		$( '.tabs' ).tabs();

		// Fitvids
		Noise.$body.fitVids();

		// Countdown Timer
		$( '#countdown' ).countdown();

		// To Go Top
		$( '#scroll-top' ).on( 'click', function ( event )
		{
			event.preventDefault();
			Noise.$HtmlBody.animate( {scrollTop: 0}, { duration: 1200, easing: 'easeInOutExpo' } );
		} );

		// Scroll to menu when click to caption of slider
		$( "#section-slider" ).on( 'click', '.caption', function ( event )
		{
			event.preventDefault();
			Noise.$HtmlBody.stop().animate( {
				scrollTop: Noise.wHeight
			}, { duration: 1000, easing: 'easeInOutExpo' } );
		} );

		// Contact Form
		var $toggleContact = $( '#toggle-contact-form' );
		$toggleContact.on( 'click', function ( event )
		{
			event.preventDefault();
			$toggleContact.toggleClass( 'entypo-cross' );

			$toggleContact.siblings( '.contact-form' ).fadeToggle();
		} );

		$( '.send-mail' ).on( 'click', function ( event )
		{
			event.preventDefault();
			var $form = $( this ).parents( '.form' ),
				$notices = $form.find( '.notices' ),
				name = $form.find( 'input[name=name]' ).val(),
				email = $form.find( 'input[name=email]' ).val(),
				message = $form.find( 'textarea' ).val(),
				$children = $notices.children();

			if ( !name || !email || !message )
			{
				$children.hide().filter( '.miss' ).fadeIn();
				return;
			}

			$children.hide().filter( '.processing' ).fadeIn();
			$.post(
				noiseVars.ajax_url,
				{
					action : 'noise_send_email',
					name   : name,
					email  : email,
					message: message
				},
				function ( response )
				{
					if ( !response.success )
						$children.hide().filter( '.' + response.data ).fadeIn();
					else
						$children.hide().filter( '.success' ).fadeIn();
				},
				'json'
			);
		} );

		// Align middle image in blog header
		var childHeight = $('.caption-holder').height();
		$('.section-image').css('margin-top', "-" + childHeight / 2 + "px");

	} );
})( jQuery );

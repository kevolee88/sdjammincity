var Noise = Noise || {};

(function ( $ )
{
	$.expr[":"].onScreen = function ( elem )
	{
		var viewport_top = Noise.$window.scrollTop(),
			viewport_bottom = viewport_top + Noise.wHeight,
			$elem = $( elem ),
			top = $elem.offset().top,
			height = $elem.height(),
			bottom = top + height;

		return (top >= viewport_top && top < viewport_bottom) ||
			(bottom > viewport_top && bottom <= viewport_bottom) ||
			(height > Noise.wHeight && top <= viewport_top && bottom >= viewport_bottom)
	};

	$.fn.isOnTopScreen = function ( offset )
	{
		if ( typeof offset === "undefined" || offset === null )
			offset = 0;

		var viewport = {
			top : Noise.$window.scrollTop(),
			left: Noise.$window.scrollLeft()
		};

		viewport.right = viewport.left + Noise.wWidth;
		viewport.bottom = viewport.top + Noise.wHeight;
		viewport.top += offset;

		var bounds = this.offset();
		bounds.right = bounds.left + this.outerWidth();
		bounds.bottom = bounds.top + this.outerHeight();

		return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom) && bounds.top < viewport.top );
	};

	Noise.sharrre = function ( container )
	{
		container = container ? container : document;
		var $container = $( container );

		$container.find( '.social-likes' ).socialLikes();
	};

	Noise.imageSlideShowInit = function ()
	{
		$( '#images-gallery-slideshow' ).flexslider( {
			animation    : "slide",
			animationLoop: true,
			easing       : 'easeInOutExpo',
			slideshow    : true,
			controlNav   : false,
			directionNav : true,
			touch        : true,
			start        : function ( slider )
			{
				var total = slider.count;
				total = 10 > total ? '0' + total : total;

				$( '.total-slides' ).text( total );

				if ( 1 >= total )
					$( slider ).find( '.flex-direction-nav' ).hide();
			},
			after        : function ( slider )
			{
				var current = slider.currentSlide + 1;
				current = 10 > current ? '0' + current : current;

				$( '.current-slide' ).text( current );
			}
		} );
	};

	Noise.openImagesLightBox = function ( element, id )
	{
		var width = $( '.inner' ).first().width(),
			ratio = 540 / 1150;
		$( element ).colorbox( {
			ajax      : true,
			href      : noiseVars.ajax_url,
			data      : { action: 'noise_get_images_gallery', id: id },
			width     : width,
			height    : ratio * width + 51,
			fixed     : true,
			close     : '<span class="entypo-cross close"></span>',
			onComplete: function ()
			{
				Noise.imageSlideShowInit();
				Noise.sharrre( '#cboxLoadedContent' );
			}
		} );
	};

	Noise.initScrollBar = function ( element )
	{
		var $this = $( element ),
			$nav;

		$this.mCustomScrollbar( 'destroy' );
		$this.mCustomScrollbar(
			{
				scrollButtons: {
					enable: true
				}
			} );

		if ( !$this.hasClass( 'top-scroll-bar' ) )
			return;

		$nav = $this.prev( '.custom-scroll-nav' );

		var $scrollTools = $this.find( '.mCSB_scrollTools' );
		if ( $scrollTools.length && $scrollTools[0].style.display == 'none' )
		{
			$nav.hide();
			return;
		}
		else {
			$nav.show();
		}

		if ( !$nav.length )
		{
			$this.before( '<div class="custom-scroll-nav"><span class="scroll-up entypo-arrow-up4"></span><span class="scroll-down entypo-arrow-down5"></span></div>' );
			$nav = $this.prev( '.custom-scroll-nav' );
		}

		$nav.on( 'click', 'span', function ( event )
		{
			event.preventDefault();
			var dir = $( this ).hasClass( 'scroll-up' ) ? 'up' : 'down',
				step = 100,
				current = Math.abs( parseInt( $this.find( '.mCSB_container' ).position().top ) ),
				next = 'up' == dir ? Math.max( 0, current - step ) : current + step;

			$this.mCustomScrollbar( 'scrollTo', next );
		} );
	};

	Noise.updateScrollBar = function ( element, reset )
	{
		var $this = $( element );
		$this.mCustomScrollbar( 'update' );

		if ( reset )
			$this.mCustomScrollbar( 'scrollTo', 'top' );
	};

	/** Fire on document ready */
	$( function ()
	{
		var $artistSlider = $( '#artists-slider' ),
			$sectionEvents = $( '#section-events' ),
			$releaseSlider = $( '#releases'),
			carouselOpt = { axis: 'y', infinite: false, easing: 'easeInOutExpo' };

		// Custom scrollbar
		$( '.scroll-pane' ).each( function ()
		{
			Noise.initScrollBar( this );
		} );

		// Twitter slider
		$( '#tweets-slider' ).flexslider( {
			animation    : 'slide',
			slideshow    : noiseVars.tweetsAuto,
			easing       : 'easeInOutExpo',
			directionNav : false,
			useCSS       : Noise.useCSSTransition
		} );

		// Quotes slider
		$( '#quotes-slider' ).flexslider( {
			animation    : 'slide',
			slideshow    : noiseVars.quotesAuto,
			easing       : 'easeInOutExpo',
			directionNav : false,
			useCSS       : Noise.useCSSTransition
		} );

		// Media slider
		$( '#latest-tracks-slider' ).tinycarousel( carouselOpt );
		$( '#media-photos-slider' ).tinycarousel( carouselOpt );
		$( '#media-videos-slider' ).tinycarousel( carouselOpt );
		$( '#products-slider' ).tinycarousel( carouselOpt );

		var $tabsSection = $( '.tabs-title' );
		$tabsSection.each( function()
		{
			var $this = $( this ),
				$sectionTitle = $this.find( '.section-title' ),
				$sectionDesc = $sectionTitle.find( '.desc' ),
				defaultDesc = $sectionDesc.text();

			if ( $sectionDesc.length )
				$sectionDesc[0].style.left = $sectionDesc.position().left + 'px';

			$this.on( 'click', '.tabs-nav a', function( event )
			{
				event.preventDefault();

				var $el = $( this ),
					text = $el.text();

				if ( $el.parent( 'li' ).is( ':first-child' ) )
					text = defaultDesc;

				$sectionDesc.stop( true, true ).hide().text( text ).fadeIn();
			} );
		} );

		$( '.open-gallery' ).on( 'click', function ( event )
		{
			event.preventDefault();
			$( '.video .parallax' ).css('position', " static ");
			Noise.openImagesLightBox( this, $( this ).data( 'id' ) );
		} );

		Noise.$body.on( 'click', '#cboxClose', function ( event )
		{
			$( '.video .parallax' ).css('position', " absolute ");
		} );

		// Events
		$( '#upcoming-events' ).tinycarousel( carouselOpt );
		$( '#past-events' ).tinycarousel( carouselOpt );

		$sectionEvents.on( 'click', '.infor-event', function ()
		{
			var $this = $( this ),
				info = $this.next().text(),
				$info = $sectionEvents.find( '.content-infor-event' );

			if ( $this.hasClass( 'active' ) )
			{
				var duration = $info.is( ':onScreen' ) ? 500 : 0;
				$info.stop( true, true ).slideUp( duration, function()
				{
					$info.children( '.show-infor-event' ).empty();
					$this.removeClass( 'active' );
				} );

				return;
			}

			$sectionEvents.find( '.infor-event.active' ).removeClass( 'active' );
			$this.addClass( 'active' );

			if ( $info.is( ':visible' ) )
			{
				$info.children( '.show-infor-event' ).hide().empty().html( info ).fadeIn();
				scrollToInfo();
			}
			else
			{
				var duration = $info.is( ':onScreen' ) ? 500 : 0;
				$info.children( '.show-infor-event' ).empty().html( info );
				$info.stop( true, true ).slideDown( duration, scrollToInfo );
			}

			function scrollToInfo()
			{
				if ( noiseVars.isMobile )
					return;

				if ( !$info.is( ':onScreen' ) )
				{
					var offset = $info.offset().top + 2 * $info.height() - Noise.wHeight;
					Noise.$HtmlBody.animate( {scrollTop: offset}, { duration: 500, easing: 'easeInOutExpo' } );
				}
			}
		} );

		$sectionEvents.on( 'click', '.close-infor', function ()
		{
			$( this ).parent( '.content-infor-event' ).slideUp();
			$sectionEvents.find( '.infor-event.active' ).removeClass( 'active' );
		} );

		// Countdown video background
		var $sectionCountdown = $( '#section-countdown' );
		if ( $sectionCountdown.length && $sectionCountdown.hasClass( 'video' ) )
		{
			var $cdVideoBg = $( '#countdown-video-bg' ),
				width = $sectionCountdown.outerWidth(),
				height = $sectionCountdown.outerHeight();

			if ( $cdVideoBg.length )
			{
				$cdVideoBg.mediaelementplayer( {
					videoWidth       : width,
					videoHeight      : height,
					loop             : true,
					pauseOtherPlayers: false,
					startVolume      : 0
				} );
			}
		}

		// Releases slider
		Noise.albumSlider = $releaseSlider.flexslider( {
			animation     : "slide",
			direction     : "vertical",
			animationLoop : false,
			easing        : 'easeInOutExpo',
			animationSpeed: 600,
			slideshow     : false,
			controlNav    : false,
			touch         : false,
			useCSS        : false
		} );

		// Release slider item on select
		$releaseSlider.on( 'click', '.flex-viewport .view-detail', function ( event )
		{
			event.preventDefault();
			Noise.albumSlider.addClass( 'item-expanded' );

			var $album = $( this ).parents( 'li' ),
				initShare = Noise.albumSlider.data( 'share' );

			if ( !initShare )
			{
				Noise.sharrre( Noise.albumSlider );
				Noise.albumSlider.data( 'share', 'true' );
			}

			$album.find( '.album-cover' ).hide();
			$album.find( '.hentry, .album-share' ).stop( true, true ).fadeIn( 800, function ()
			{
				$( this ).find( '.scroll-pane' ).each( function ()
				{
					Noise.initScrollBar( this );
				} );

				// Open all slide
				$album.siblings( 'li' ).children( '.album-cover' ).hide().siblings( '.hentry, .album-share' ).show( 0, function ()
				{
					$( this ).find( '.scroll-pane' ).each( function ()
					{
						Noise.initScrollBar( this );
					} );
				} );
			} );
		} );

		// Release slider item close
		$releaseSlider.on( 'click', '.hide-detail', function ( event )
		{
			event.preventDefault();

			// albumSlider.find( '.flex-active-slide' ).children( 'a' ).fadeIn().next( '.hentry, .album-likes' ).fadeOut();
			Noise.albumSlider.find( '.flex-viewport .album-cover' ).fadeIn().next( '.hentry, .album-share' ).fadeOut();

			Noise.albumSlider.removeClass( 'item-expanded' );
		} );

		// Artists
		$artistSlider.flexslider(
		{
			animation     : "slide",
			direction     : "vertical",
			easing        : 'easeInOutExpo',
			animationSpeed: 600,
			animationLoop : false,
			slideshow     : false,
			controlNav    : false,
			directionNav  : true,
			touch         : false,
			useCSS        : false,
			start         : function ( slider )
			{
				if ( slider.count <= 1 )
					slider.directionNav.hide();

				// Artist direction nav
				$( '.artist-direction-nav' ).on( 'click', 'a', function ( event )
				{
					event.preventDefault();

					var $this = $( this ),
						$artistNav = $this.parents( '.artist-direction-nav' );

					if ( $this.hasClass( 'disable' ) || slider.hasClass( 'item-moving' ) )
						return;

					var action = $this.hasClass( 'artist-next' ) ? 'next' : 'prev',
						currentSlide = slider.currentSlide,
						slide = false,
						$artist,
						$current = $( slider.slides[slider.currentSlide] ).children( '.hentry:visible' );

					slider.addClass( 'item-moving' );
					if ( 'next' == action )
					{
						$artist = $current.next( '.hentry' );

						if ( $artist.length <= 0 )
						{
							slide = true;
							currentSlide++;
							$artist = $( slider.slides[slider.getTarget( 'next' )] ).children( '.hentry:first-child' );
						}
					}
					else
					{
						$artist = $current.prev( '.hentry' );

						if ( $artist.length <= 0 )
						{
							slide = true;
							currentSlide--;
							$artist = $( slider.slides[slider.getTarget( 'prev' )] ).children( '.hentry:last-child' );
						}
					}

					if ( slide )
					{
						var width = $artist.width();

						$artist.addClass( 'active' ).show();
						$artist.siblings( '.hentry' ).addClass( 'hidden' ).hide();

						slider.flexslider( action );

						setTimeout( function ()
						{
							var margin = Noise.wWidth <= 800 ? 0 : 30;

							$current.removeClass( 'active' ).attr( 'style', '' );
							$current.siblings( '.hentry' ).removeClass( 'hidden' ).attr( 'style', '' );
							slider.removeClass( 'item-moving' );
						}, slider.vars.animationSpeed );
					}
					else
					{
						var offset = 'next' == action ? slider.h : -slider.h;

						$artist[0].style.position = 'absolute';
						$artist[0].style.top = offset + 'px';
						$artist.removeClass( 'hidden' ).addClass( 'active' ).show().children( '.artist-info' ).show();

						$current[0].style.position = 'absolute';
						$current[0].style.top = 0;
						$current.stop( true, true ).animate( { top: -offset }, slider.vars.animationSpeed, 'easeInOutExpo', function ()
						{
							$( this ).children( '.artist-info' ).hide();
						} );

						$artist.stop( true, true ).animate( { top: 0 }, slider.vars.animationSpeed, 'easeInOutExpo', function ()
						{
							$artist.attr( 'style', '' );
							$current.removeClass( 'active' ).addClass( 'hidden' ).attr( 'style', '' );
							slider.removeClass( 'item-moving' );
						} );
					}

					// Toggle disable class
					$this.siblings( 'a' ).removeClass( 'disable' );

					if ( currentSlide == slider.last && $artist.next( '.hentry' ).length <= 0 )
						$artistNav.children( '.artist-next' ).addClass( 'disable' );

					if ( currentSlide == 0 && $artist.prev( '.hentry' ).length <= 0 )
						$artistNav.children( '.artist-prev' ).addClass( 'disable' );

					// Set artist gallery id
					$( slider ).children( '.view-artist-gallery' ).data( 'id', $artist.data( 'id' ) ).text( $artist.find( '.artist-gallery-link' ).text() );

					// Init mcustomscroll
					$artist.find( '.scroll-pane' ).each( function ()
					{
						Noise.initScrollBar( this, true );
					} );
				} );
			}
		} );

		// Click view artist detail
		$artistSlider.on( 'click', '.view-artist, .artist-thumbnail .overlay', function ( event )
		{
			event.preventDefault();

			var $this = $( this ),
				$current = $this.parents( '.hentry' ),
				$slider = $this.parents( '.artists-slider' ),
				$artists = $current.siblings( '.hentry' ),
				gallery = $this.siblings( '.artist-gallery-link' ).html();

			$slider.addClass( 'item-expanding' );

			if ( $artists.length > 0 )
			{
				var duration = Noise.wWidth <= 480 ? 0 : 500;
				$artists.stop().animate(
					{ width: 0, marginLeft: 0 },
					duration,
					function ()
					{
						$( this ).addClass( 'hidden' ).hide().attr( 'style', '' );

						showArtistDetail( false );
					}
				);

				$current.animate( { marginLeft: 0 }, 500, function() { $current.addClass( 'active' ) } );
			}
			else
			{
				showArtistDetail( true );
			}

			function showArtistDetail( show )
			{
				if ( show )
					$current.addClass( 'active' );

				$this.parents( '.artist-thumbnail' ).next( '.artist-info' ).fadeIn( 500, function ()
				{
					$slider.removeClass( 'item-expanding' ).addClass( 'item-expanded' );
					$slider.children( '.hide-detail' ).fadeIn( 500 );
				} );

				$slider.children( '.view-artist-gallery' ).data( 'id', $this.data( 'id' ) ).html( gallery ).fadeIn();

				// Init mcustomscroll
				$current.find( '.scroll-pane' ).each( function ()
				{
					Noise.initScrollBar( this );
				} );
			}

			// Add class disable from artist direction nav
			var $nav = $slider.find( '.artist-direction-nav' ),
				$next = $nav.find( '.artist-next' ),
				$prev = $nav.find( '.artist-prev' ),
				$nextArtist = $current.next( '.hentry' ),
				$prevArtist = $current.prev( '.hentry' ),
				$group = $this.parents( 'li' ),
				$nextGroup = $group.next( 'li' ),
				$prevGroup = $group.prev( 'li' );

			if ( $nextArtist.length <= 0 && $nextGroup.length <= 1 )
				$next.addClass( 'disable' );

			if ( $prevArtist.length <= 0 || $prevGroup.length <= 0 )
				$prev.addClass( 'disable' );

			// Remove class disable from artist direction nav
			if ( $nextArtist.length >= 1 || $nextGroup.length >= 1 )
				$next.removeClass( 'disable' );

			if ( $prevArtist.length >= 1 ||$prevGroup.length >= 1 )
				$prev.removeClass( 'disable' );
		} );

		// Show image number if artist section is artist single
		$artist_single = $( '.artist-single' );
		$artist_single.find( '.artist-thumbnail .overlay' ).trigger( 'click' );

		// Close artist detail
		$artistSlider.on( 'click', '.hide-detail', function ( e )
		{
			e.preventDefault();

			// var $artistSlider = $( this ).parents( '.artists-slider' ),
			var	$artists = $artistSlider.find( '.flex-viewport .flex-active-slide' ).children( '.hentry' ),
				$current = $artists.filter( ':visible' ),
				margin = Noise.wWidth <= 800 ? 0 : 30;

			$artistSlider.children( '.view-artist-gallery' ).fadeOut();
			$current.children( '.artist-info' ).fadeOut( 300, function ()
			{
				var $this = $( this ),
					$current = $this.parents( '.hentry' ),
					$hidden = $artists.filter( '.hidden' ),
					$hiddenNext = $hidden.not( ':first-child' ),
					width;

				$current.removeClass( 'active' );
				width = $current.attr( 'style', '' ).width();

				if ( $hiddenNext.length )
					$hiddenNext[0].style.marginLeft = margin + 'px';

				$hidden.show().animate( { width: width }, 500, function ()
				{
					$hidden.removeClass( 'hidden' ).attr( 'style', '' );
				} );

				$artistSlider.removeClass( 'item-expanded' );
			} );
		} );

		// Make artist slider responsive
		Noise.$window.resize( function ()
		{
			var $currentSlide = $artistSlider.find( '.slides .flex-active-slide' );
			if ( !$artistSlider.hasClass( 'item-expanded' ) )
			{
				$artistSlider.find( '.hentry' ).attr( 'style', '' );

				if ( $currentSlide.is( ':first-child' ) )
					$artistSlider.find( '.artist-direction-nav .artist-prev' ).addClass( 'disable' );

				if ( $currentSlide.is( ':last-child' ) )
					$artistSlider.find( '.artist-direction-nav .artist-next' ).addClass( 'disable' );
			}
		} );

		// Display player icon when scroll
		var $player = $( '#section-player' ),
			$tracksInfo = $player.find( '#tracks-info' ),
			playerTop = $( '#section-slider' ).length ? Noise.wHeight : 0; // wHeight = slider height

		// Scroll to player when click on sticky icon
		$( '#player-icon' ).on( 'click', function ()
		{
			if ( $tracksInfo.is( ':visible' ) )
			{
				$player.find( '.mejs-info' ).trigger( 'click' );
			}
			$player.toggleClass( 'showdown' );
			// Noise.$HtmlBody.animate( {scrollTop: playerTop}, { duration: 1200, easing: 'easeInOutExpo' } );
		} );

		// Highlight current link per visible section
		if ( !window.location.origin )
			window.location.origin = window.location.protocol + "//" + window.location.host;
		var baseURI = window.location.origin + window.location.pathname,
			$menuLinks = Noise.$navMenu.find( '.menu a' );

		function highLightMenuItem()
		{
			if ( noiseVars.isMobile )
				return;

			var menuHeight = Noise.$body.hasClass( 'sticky-nav' ) ? Noise.navHeight : 0,
				found = false;

			$menuLinks.each( function ()
			{
				var target = this.hash;

				if ( target == '' || this.baseURI != baseURI || found )
					return;

				$menuLinks.removeClass( 'active' );
				var $section = $( target );

				if ( $section.length && $section.isOnTopScreen( menuHeight ) )
				{
					$( this ).addClass( 'active' );
					found = true;
				}
			} );
		}

		Noise.$window.on( 'scroll', function ()
		{
			highLightMenuItem();
		} );

		// Menu Smooth
		Noise.$navMenu.on( 'click', '.menu a', function ( e )
		{
			var target = this.hash,
				href = $( this ).attr( 'href' ),
				top;

			if ( target == '' || href.replace( target, '' ) != baseURI )
				return;

			e.preventDefault();

			var $section = $( target );
			if ( !$section.length )
				return;

			// Select first section after menu
			// We have to check it because someone want to remove the player
			var $firstSection = null;
			if ( $player.length )
				$firstSection = $player.next();
			else
				$firstSection = Noise.$navMenu.next();

			top = $section.offset().top - Noise.navHeight + 2;
			if ( $section.get(0) == $firstSection.get(0) && !Noise.$body.hasClass( 'sticky-nav' ) )
				top = top - Noise.navHeight - 40; // height of player: 40px

			$menuLinks.removeClass( 'active' );
			$( this ).addClass( 'active' );
			Noise.$HtmlBody.stop().animate( {
				scrollTop: top
			}, {
				duration: 1200,
				easing  : 'easeInOutExpo'
			} );
		} );

		// Click to logo
		$( '.logo a' ).on( 'click', function ( event )
		{
			event.preventDefault();
			Noise.$HtmlBody.animate( {scrollTop: 0}, { duration: 1200, easing: 'easeInOutExpo' } );
		} );

		// Responsive colorbox
		Noise.$window.resize( function()
		{
			var ratio = 540 / 1150,
				width = $( '.inner' ).first().width(),
				height = ratio * width + 51;

			$.colorbox.resize( {
				height: height,
				width: width
			} );
		} );
		window.addEventListener( "orientationchange", function ()
		{
			if ( $( '#cboxOverlay' ).is( ':visible' ) )
			{
				$.colorbox.load( true );
			}
		}, false );
	} );
})( jQuery );

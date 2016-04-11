"use strict";

// Namespace
var Noise = Noise || {};

/**
 * Mimic default WordPress handler
 * @see wp-includes/js/mediaelement.js/wp-mediaelement.js
 */
( function ( $ )
{
	// Add mime-type aliases to MediaElement plugin support
	mejs.plugins.silverlight[0].types.push( 'video/x-ms-wmv' );
	mejs.plugins.silverlight[0].types.push( 'audio/x-ms-wma' );

	Noise.openVideosLightBox = function ( element, id )
	{
		var width = $( '.inner' ).first().width(),
			ratio = 600 / 1170;
		Noise.videoSlideshow = $( element ).colorbox( {
			ajax      : true,
			href      : noiseVars.ajax_url,
			data      : { action: 'noise_get_video_player', id: id },
			width     : width,
			height    : ratio * width,
			fixed     : true,
			className : 'video-player',
			close     : '<i class="entypo-cross"></i>',
			onComplete: function ()
			{
				// Init videos player
				var settings = {
					features          : ['playpause', 'progress', 'current', 'duration', 'volume', 'fullscreen'],
					alwaysShowControls: false,
					pauseOtherPlayers : true,
					enableAutosize    : false,
					success           : function ( player, node )
					{
						player.addEventListener( 'ended', function ()
						{
							Noise.videoSlideshow.flexslider( 'next' );
						} );
					}
				};

				$( 'video' ).each( function ()
				{
					$( this ).mediaelementplayer( settings );
				} );

				// Init videos slideshow
				Noise.videoSlideshow = $( '.videos-slideshow' ).flexslider( {
					animation    : "slide",
					animationLoop: false,
					slideshow    : false,
					controlNav   : false,
					start: function( slider )
					{
						$( window ).trigger( 'resize' );

						if ( 1 >= slider.count )
							$( slider ).find( '.flex-direction-nav' ).hide();
					}
				} );
			}
		} );
	};

	$( function ()
	{
		function updateScrollBar()
		{
			$( '#tracks-info:visible .track-info.current' ).find( '.scroll-pane' ).each( function ()
			{
				$( this ).mCustomScrollbar( 'update' );
				$( this ).mCustomScrollbar( 'scrollTo', 'top' );
			} );
		}

		function updateExtendedPlayer( player )
		{
			// Update default player
			Noise.player.setCurrentTrack( player.currentTrack );
			Noise.player.setSrc( player.currentTrack.data( 'url' ) );
			Noise.player.setCurrentRail();
			Noise.player.updateTrackTitle();

			var $playerBox = $( '#cboxLoadedContent .track-visual' ),
				$trackDetail = $( '#tracks-detail #track-detail-' + player.currentTrack.data( 'id' ) ),
				$image = $trackDetail.find( '.track-meta .image' ).clone();

			$image.removeClass( 'detail-image' ).append( '<canvas id="visualization" class="visualization" height="120" width="1150"></canvas>' );
			$playerBox.children( '.image' ).first().attr( 'style', $image.attr( 'style' ) ).children( '.caption' ).remove();
			$playerBox.children( '.image' ).first().prepend( $image.find( '.caption' ) );

			if ( $playerBox.find( '.details' ).is( ':visible' ) )
			{
				$playerBox.find( '.image' ).first().hide();
				$playerBox.find( '.details' ).html( $trackDetail.html() );
				$playerBox.find( '.details .scroll-pane' ).each( function ()
				{
					Noise.initScrollBar( this );
				} );
			}
		}

		// Disable spectrum on non-webkit browser
		if ( typeof window.webkitAudioContext == 'undefined' )
			$( 'body' ).addClass( 'no-spectrum' );

		var settings = {
			features          : ['icon', 'playlist', 'prev', 'playpause', 'next', 'tracktitle', 'rating', 'progress', 'current', 'duration', 'volume', 'info'],
			alwaysShowControls: true,
			audioHeight       : 40,
			loop              : false,
			shuffle           : false,
			playlist          : true,
			pauseOtherPlayers : false,
			afterNext         : updateScrollBar,
			afterPrev         : updateScrollBar,
			infoShow          : function()
			{
				updateScrollBar();
				// Noise.initParallax();
				Noise.$body.addClass( 'track-info-showed' );
			},
			infoHide          : function()
			{
				// Noise.initParallax();
				Noise.$body.removeClass( 'track-info-showed' );
			},
			success           : function ( mediaElement, domObject )
			{
				mediaElement.addEventListener( 'ended', function ()
				{
					if ( !Noise.player.options.loop || ( Noise.player.options.playAll && Noise.player.options.loop ) )
					{
						$( '.play-track.entypo-pause' ).removeClass( 'entypo-pause' );
						$( 'li.playing' ).removeClass( 'playing' );
					}

					if ( !Noise.player.options.loop && Noise.player.options.playAll && Noise.player.currentTrack.is( ':last-child' ) )
					{
						$( '.play-album.entypo-pause' ).removeClass( 'entypo-pause' );
					}

					// Replay list
					if ( !Noise.player.options.loop || Noise.player.options.playAll )
					{
						Noise.player.playNextTrack();
					}
				}, false );

				mediaElement.addEventListener( 'pause', function ()
				{
					$( '.play-track.entypo-pause, .play-album.entypo-pause' ).removeClass( 'entypo-pause' ).addClass( 'paused' );
				}, false );

				mediaElement.addEventListener( 'play', function ()
				{
					$( '.play-track.paused, .play-album.paused' ).addClass( 'entypo-pause' ).removeClass( 'paused' );

					// Set played track
					Noise.player.currentTrack.addClass( 'played' );
				}, false );
			}
		};

		if ( typeof _wpmejsSettings !== 'undefined' )
			settings.pluginPath = _wpmejsSettings.pluginPath;

		if ( $( '#player' ).length )
		{
			Noise.player = new MediaElementPlayer( '#player', settings );

			if ( noiseVars.autoplay )
			{
				Noise.player.load();
				Noise.player.play();
			}
		}

		// Loop and shuffle
		var $shuffles = $( '.player-playshuffle' ),
			$loops = $( '.player-playloop' ),
			$body = $( 'body' );
		$body.on( 'click', '.player-playshuffle', function ()
		{
			$shuffles.toggleClass( 'active' );
			Noise.player.shuffleToggle();
		} );
		$body.on( 'click', '.player-playloop', function ()
		{
			$loops.toggleClass( 'active' );
			Noise.player.loopToggle();
		} );

		// Open extended player
		var extendedSettings = {
			features          : ['icon', 'playlist', 'prev', 'playpause', 'next', 'shuffle', 'loop', 'progress', 'current', 'duration', 'volume', 'minimize'],
			alwaysShowControls: true,
			audioHeight       : 40,
			loop              : false,
			shuffle           : false,
			playlist          : true,
			pauseOtherPlayers : false,
			success           : function ( mediaElement, domObject )
			{
				var firstLoad = true;

				mediaElement.addEventListener( 'loadedmetadata', function ()
				{
					if ( !firstLoad )
						return;

					var currentTime = Noise.player.getCurrentTime();
					if ( 0 != currentTime )
						mediaElement.setCurrentTime( currentTime );

					firstLoad = false;
				}, false );

				mediaElement.addEventListener( 'play', function ()
				{
					Noise.player.pause();
				}, false );
			},
			afterNext         : function ( mediaElement )
			{
				updateExtendedPlayer( mediaElement );
			},
			afterPrev         : function ( mediaElement )
			{
				updateExtendedPlayer( mediaElement );
			}
		};
		if ( typeof _wpmejsSettings !== 'undefined' )
			extendedSettings.pluginPath = _wpmejsSettings.pluginPath;
		$( '#tracks-info' ).on( 'click', '.go-extended a', function ()
		{
			var width = $( '.inner' ).first().width();
			$( this ).colorbox( {
				ajax        : true,
				width       : width,
				height      : 600,
				fixed       : true,
				overlayClose: false,
				className   : 'extended-player',
				close       : '<i class="entypo-cross"></i>',
				onComplete  : function ()
				{
					Noise.Spectrum.init();
					Noise.extendedPlayer = new MediaElementPlayer( '#extended-player', extendedSettings );
					Noise.extendedPlayer.setSrc( Noise.player.currentTrack.data( 'url' ) );
					Noise.extendedPlayer.load();
					Noise.extendedPlayer.play();
				},
				onClosed: function()
				{
					if ( Noise.$body.hasClass( 'player-minimized' ) )
						Noise.$body.removeClass( 'player-minimized' );
					else
						Noise.player.container.find( '.mejs-info' ).trigger('click');
				}
			} );
		} );

		// View track detail on extended player
		$body.on( 'click', '.extended-player .caption a', function ( event )
		{
			event.preventDefault();

			var $this = $( this ),
				$track = $this.parents( '.track-visual' ),
				$details = $track.children( '.details' ),
				id = $this.data( 'id' );

			// Check if in detail view
			if ( $this.parents( '.image' ).hasClass( 'detail-image' ) )
			{
				$details.fadeOut();
				$track.children( '.image' ).fadeIn();
				return;
			}

			if ( $details.is( ':empty' ) || $details.data( 'id' ) != id )
			{
				var $tracksDetail = $( '#tracks-detail' ),
					$trackDetail = $tracksDetail.children( '#track-detail-' + $this.data( 'id' ) );

				if ( $trackDetail.length )
				{
					$track.children( '.details' ).data( 'id', id ).html( $trackDetail.html() ).fadeIn( 500, function ()
					{
						$( this ).find( '.track-lyric .scroll-pane' ).each( function ()
						{
							Noise.initScrollBar( this )
						} );
					} );
					$this.parents( '.image' ).fadeOut();
				}
				else
				{
					var $loading = $( '<div class="loading"></div>' ).appendTo( $this.closest( '.caption' ) );

					$.post(
						noiseVars.ajax_url,
						{
							action: 'noise_get_track_detail',
							id    : id
						},
						function ( response )
						{
							$loading.remove();
							$tracksDetail.append( response );
							$details.html( response ).fadeIn( 500, function ()
							{
								$( this ).find( '.track-lyric .scroll-pane' ).each( function ()
								{
									Noise.initScrollBar( this )
								} );
							} );
							$this.parents( '.image' ).fadeOut();
						}
					);
				}
				// Update Views
				$.post(
					noiseVars.ajax_url,
					{
						action: 'noise_update_views',
						id    : id
					},
					function ( response ){},
					'json'
				);
			}
			else
			{
				$details.fadeIn();
				$this.parents( '.image' ).fadeOut();
			}
		} );

		// Vote track
		$body.on( 'hover', '.vote-ui .heart', function ( event )
		{
			var $this = $( this ),
				$vote = $this.closest( '.vote-ui' );

			if ( $vote.hasClass( 'track-voted' ) )
				return;

			if ( 'mouseenter' == event.type )
			{
				$this.addClass( 'setted' ).removeClass( 'unset' )
					.prevAll( '.heart' ).addClass( 'setted' ).removeClass( 'unset' );
				$this.nextAll( '.heart' ).addClass( 'unset' );
			}

			if ( 'mouseout' == event.type )
			{
				$this.removeClass( 'setted unset' ).siblings( '.heart' ).removeClass( 'setted unset' );
			}
		} );
		$body.on( 'mouseout', '.vote-ui', function ()
		{
			var $this = $( this );

			if ( $this.hasClass( 'track-voted' ) )
				return;

			$this.children( '.heart' ).removeClass( 'setted unset' );
		} );
		$body.on( 'click', '.vote-ui .heart', function ( event )
		{
			event.preventDefault();
			var $this = $( this ),
				$vote = $this.closest( '.vote-ui' ),
				id = $vote.data( 'id' );

			if ( $vote.hasClass( 'track-voted' ) )
				return;

			$this.addClass( 'entypo-heart' ).removeClass( 'entypo-heart2 unset setted' )
				.prevAll( '.heart' ).addClass( 'entypo-heart' ).removeClass( 'entypo-heart2 unset setted' );
			$this.nextAll( '.heart' ).addClass( 'entypo-heart2' ).removeClass( 'entypo-heart unset setted' );

			$.post(
				noiseVars.ajax_url,
				{
					action: 'noise_update_vote',
					id    : id,
					score : $this.index() + 1,
					nonce : $vote.data( 'nonce' )
				},
				function ( response )
				{
					if ( response.success )
					{
						$vote.addClass( 'track-voted' );
						$( '.track-vote-' + id ).addClass( 'track-voted' );

						var content = $this.parents( '.details' ).html();
						$( '#tracks-detail' ).children( '#track-detail-' + id ).html( content );
					}
				},
				'json'
			);
		} );

		// Play selected track
		$body.on( 'click', '.play-track', function ( event )
		{
			event.preventDefault();
			var $this = $( this ),
				src = $this.data( 'url' ),
				id = $this.data( 'id' );

			if ( !src )
			{
				alert( 'Invalid Source' );
				return;
			}

			// Pause
			if ( $this.hasClass( 'entypo-pause' ) )
			{
				Noise.player.pause();
				return;
			}

			// Resume
			if ( $this.hasClass( 'paused' ) )
			{
				Noise.player.play();
				return;
			}

			// Play
			$( '.play-track' ).removeClass( 'entypo-pause paused' );
			$this.addClass( 'entypo-pause' );

			Noise.player.setSrc( src );
			Noise.player.load();
			Noise.player.play();

			var $currentTrack = Noise.player.playlist.children( 'li' ).filter( function ()
			{
				return $( this ).data( 'id' ) == id;
			} );
			if ( !$currentTrack.length )
			{
				$currentTrack = $( '<li/>' )
					.attr( 'title', $this.data( 'title' ) )
					.data( {
						url: src,
						id : id,
						rate: $this.data( 'rate' ),
						nonce: $this.data( 'nonce' )
					} )
					.appendTo( Noise.player.playlist );

			}
			Noise.player.setCurrentTrack( $currentTrack );
			Noise.player.updateTrackTitle();
			Noise.player.updateTrackRating();

			// Switch track info
			var $tracksInfo = $( '#tracks-info' );
			if ( $tracksInfo.children( '#track-info-' + id ).length > 0 )
			{
				$tracksInfo.children( '.current' ).removeClass( 'current' );
				$tracksInfo.children( '#track-info-' + id ).addClass( 'current' );
			}
			else
			{
				var data = {
					action: 'noise_get_track_info',
					id    : id
				};

				$.post( noiseVars.ajax_url, data, function ( response )
				{
					$tracksInfo.append( response );
					$tracksInfo.children( '.current' ).removeClass( 'current' );
					$tracksInfo.children( '#track-info-' + id ).addClass( 'current' );
				} );
			}
		} );

		// Play track on Soundcloud
		$body.on( 'click', '.open-soundcloud', function ( event )
		{
			event.preventDefault();

			Noise.soundcloud = $( this ).colorbox( {
				ajax     : true,
				href     : noiseVars.ajax_url,
				data     : { action: 'noise_get_soundcloud_player', url: $( this ).attr( 'href' ) },
				width    : $( '.inner' ).first().width(),
				fixed    : true,
				className: 'soundcloud-player',
				close    : '<i class="entypo-cross"></i>'
			} );
		} );

		// Open video player
		$body.on( 'click', '.open-videos', function ( event )
		{
			event.preventDefault();
			$( '.video .parallax' ).css('position', " static ");
			Noise.openVideosLightBox( this, $( this ).data( 'id' ) );
		} );

		// Play album
		$body.on( 'click', '.play-album', function ( event )
		{
			event.preventDefault();

			var $this = $( this ),
				$tracks = $this.parents( '.album-cover' ).next( '.album' ).find( '.tracklist ol li' ),
				id = $this.data( 'id' );

			// Pause
			if ( $this.hasClass( 'entypo-pause' ) )
			{
				Noise.player.pause();
				return;
			}

			// Resume
			if ( $this.hasClass( 'paused' ) )
			{
				Noise.player.play();
				return;
			}

			// Play
			$( '.play-album' ).removeClass( 'entypo-pause paused' );
			$this.addClass( 'entypo-pause' );
			Noise.player.options.playAll = true;

			var $playable = $tracks.filter( function ()
			{
				return $( this ).find( '.play-track' ).data( 'url' ) != '';
			} );

			if ( !$playable.length )
			{
				$this.removeClass( 'entypo-pause' );
				Noise.player.options.playAll = false;
				console.log( 'No playable track' );
				return;
			}

			$playable.first().find( '.play-track' ).trigger( 'click' );

			// Update playlist
			var $track,
				$data;
			$( Noise.player.playlist ).html( '' );
			$playable.each( function ( index )
			{
				$data = $( this ).find( '.play-track' );
				$track = $( '<li></li>' );
				$track.attr( 'title', $data.data( 'title' ) )
					.text( $data.data( 'title' ) )
					.data( {
						id : $data.data( 'id' ),
						url: $data.data( 'url' )
					} );

				if ( 0 == index )
					$track.addClass( 'current played' );

				$track.appendTo( Noise.player.playlist );
			} );
		} );

		// Play track inside album
		$body.on( 'click', '.play-track', function ( event )
		{
			event.preventDefault();
			var id = $( this ).data( 'id' );

			// Set current track
			if ( id != Noise.player.currentTrack.data( 'id' ) )
			{
				var $track = Noise.player.playlist.children().filter( function ()
				{
					return $( this ).data( 'id' ) == id;
				} );

				if ( $track.length )
				{
					Noise.player.currentTrack = $track;
					Noise.player.currentTrack.addClass( 'current' ).siblings().removeClass( 'current' );
				}
			}

			var $album = $( this ).parents( '.album' );

			// This track is not in album, disable playAll option
			if ( !$album.length )
			{
				Noise.player.options.playAll = false;
				return;
			}

			if ( $( this ).data( 'url' ) != '' )
			{
				$( '.album li.playing' ).removeClass( 'playing' );
				$( this ).parents( 'li' ).addClass( 'playing' );
			}

			// Track is not in playing album
			var $playAlbum = $album.prev( '.album-cover' ).find( '.play-album' );
			if ( !$playAlbum.hasClass( 'paused' ) && !$playAlbum.hasClass( 'entypo-pause' ) )
				Noise.player.options.playAll = false;
		} );
	} );
}( jQuery ) );

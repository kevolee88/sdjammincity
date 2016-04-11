/**
 * @file MediaElement Playlist Feature (plugin).
 * @author Andrew Berezovsky <andrew.berezovsky@gmail.com>
 * Twitter handle: duozersk
 * @author Original author: Junaid Qadir Baloch <shekhanzai.baloch@gmail.com>
 * Twitter handle: jeykeu
 * Dual licensed under the MIT or GPL Version 2 licenses.
 */
(function ( $ )
{
	$.extend( mejs.MepDefaults, {
		loopText   : 'Repeat',
		shuffleText: 'Shuffle',
		nextText   : 'Next Track',
		prevText   : 'Previous Track',
		playAll    : false,
		beforeNext : function(){},
		afterNext  : function(){},
		beforePrev : function(){},
		afterPrev  : function(){},
		infoShow   : function(){},
		infoHide   : function(){}
	} );

	var $tracksInfo = $( '#tracks-info' );

	$.extend( MediaElementPlayer.prototype, {
		// ICON
		buildicon         : function ( player, controls, layers, media )
		{
			$( '<div class="mejs-control mejs-icon entypo-music"/>' ).appendTo( controls );
		},
		// INFO TOGGLE
		buildinfo         : function ( player, controls, layers, media )
		{
			var t = this;
			var info = $( '<div class="mejs-control mejs-info entypo-arrow-down5"/>' )
			.appendTo( controls )
			.click( function ()
			{
				if ( info.hasClass( 'entypo-arrow-down5' ) )
				{
					info.removeClass( 'entypo-arrow-down5' ).addClass( 'entypo-arrow-up4' );
					$tracksInfo.stop(true, true).slideDown( {
						duration: 500,
						easing: 'easeInOutExpo',
						complete: function(){ t.options.infoShow() }
					} );
				}
				else
				{
					info.addClass( 'entypo-arrow-down5' ).removeClass( 'entypo-arrow-up4' );
					$tracksInfo.stop(true, true).slideUp( {
						duration: 500,
						easing: 'easeInOutExpo',
						complete: function(){ t.options.infoHide() }
					} );
				}
			} );
		},
		// MINIMIZE
		buildminimize         : function ( player, controls, layers, media )
		{
			$( '<div class="mejs-control mejs-minimize entypo-resize-shrink"/>' )
				.appendTo( controls )
				.click( function()
				{
					player.pause();
					$( 'body' ).addClass( 'player-minimized' );
					$.colorbox.close();
				} );
		},
		// LOOP
		buildloop         : function ( player, controls, layers, media )
		{
			var loop = $( '<div class="mejs-control mejs-loop entypo-loop" title="' + this.options.loopText + '" />' )
				.appendTo( controls )
				.click( function ()
				{
					loop.toggleClass( 'active' );
					player.options.loop = !player.options.loop;
				} );
		},
		// LOOP TOGGLE
		loopToggle         : function ()
		{
			this.options.loop = !this.options.loop;
		},
		// SHUFFLE
		buildshuffle         : function ( player, controls, layers, media )
		{
			var shuffle = $( '<div class="mejs-control mejs-shuffle entypo-shuffle" title="' + this.options.shuffleText + '" />' )
				.appendTo( controls )
				.click( function ()
				{
					shuffle.toggleClass( 'active' );
					player.options.shuffle = !player.options.shuffle;
				} );
		},
		// SHUFFLE TOGGLE
		shuffleToggle      : function ()
		{
			this.options.shuffle = !this.options.shuffle;
		},
		// PREVIOUS TRACK BUTTON
		buildprev         : function ( player, controls, layers, media )
		{
			this.prevTrack = $( '<div class="mejs-control mejs-prev entypo-previous" title="' + this.options.prevText + '" />' )
				.appendTo( controls )
				.click( function ()
				{
					player.playPrevTrack();
				} );
		},
		// NEXT TRACK BUTTON
		buildnext         : function ( player, controls, layers, media )
		{
			this.nextTrack = $( '<div class="mejs-control mejs-next entypo-next" title="' + this.options.nextText + '" />' )
				.appendTo( controls )
				.click( function ()
				{
					player.playNextTrack();
				} );
		},
		// PLAYLIST WINDOW
		buildplaylist     : function ( player, controls, layers, media )
		{
			// Set the first track as current
			this.playlist = $( '#mejs-playlist' );
			var current = this.playlist.find( 'li.current' );

			if ( !current.length )
			{
				current = this.playlist.find( 'li:first' ).addClass( 'current' );
			}

			this.currentTrack = current;
			$( '#track-info-' + current.data( 'id' ) ).addClass( 'current' );

			// When current track ends - play the next one
			if ( player.options.playAll )
				media.addEventListener( 'ended', function ()
				{
					player.playNextTrack();
				}, false );
		},
		// TRACK TITLE
		buildtracktitle   : function ( player, controls, layers, media )
		{
			this.trackTitle = $( '<div class="mejs-tracktitle"/>' ).appendTo( controls );
			this.updateTrackTitle();

			$( '.mejs-tracktitle ').bind( 'marquee', function()
			{
				var $title = $(this).children( 'div' );
				$title.css({ right: -$title.width() });
				$title.animate({ right: $title.parent().width() }, 10000, 'linear', function() {
					$title.trigger( 'marquee' );
				});
			} );
		},
		// RATING
		buildrating  : function ( player, controls, layers, media )
		{
			this.trackRating = $( '<div class="mejs-rating vertical"/>' ).appendTo( controls );
			this.trackRating.append( '<span class="entypo-heart"/>' );
			this.trackRating = $( '<div class="vote-ui"/>' ).appendTo( this.trackRating );
			this.updateTrackRating();
		},
		updateTrackTitle  : function ()
		{
			if ( typeof this.trackTitle != 'undefined' )
			{
				var title = this.currentTrack.attr( 'title' );

				this.trackTitle.html( '<div>' + title + '</div>' ).attr( 'title', title );
				this.setControlsSize();

				// Trigger marqueue for long title
				if ( this.trackTitle.width() < this.trackTitle.children( 'div' ).width() )
					this.trackTitle.children( 'div' ).trigger( 'marquee' );
			}
		},
		updateTrackRating : function ()
		{
			if ( typeof this.trackRating == 'undefined' )
			{
				return;
			}

			var score = this.currentTrack.data( 'rate' ),
				nonce = this.currentTrack.data( 'nonce' ),
				id = this.currentTrack.data( 'id' ),
				hearts = new Array;

			this.trackRating.data( {
				id: id,
				nonce: nonce
			} );

			this.trackRating.attr( 'class', '' ).addClass( 'vote-ui track-vote-' + id );
			if ( !nonce )
			{
				this.trackRating.addClass( 'track-voted' );
			}
			else
			{
				this.trackRating.removeClass( 'track-voted' );
			}

			for( var rate = 1; rate <= 5; rate++  )
			{
				if ( rate <= score )
					hearts.push( '<span class="entypo-heart heart"></span>' );
				else
					hearts.push( '<span class="entypo-heart2 heart"></span>' );
			}

			this.trackRating.html( hearts.join( '' ) );
		},
		playNextTrack     : function ()
		{
			var t = this,
				tracks = t.playlist.find( 'li' ),
				current = tracks.filter( '.current' ),
				notPlayed = tracks.not( '.played' ),
				next;
			if ( notPlayed.length < 1 )
			{
				tracks.removeClass( 'played' );
				notPlayed = tracks.not( '.current' );
			}
			if ( t.options.shuffle )
			{
				var random = Math.floor( Math.random() * notPlayed.length );
				next = notPlayed.eq( random );
			}
			else
			{
				next = current.next();
				if ( next.length < 1 && t.options.loop )
				{
					next = current.siblings().first();
				}
			}
			if ( next.length == 1 )
			{
				t.options.beforeNext( t );

				next.addClass( 'played' );
				t.playTrack( next );

				t.options.afterNext( t );
			}
		},
		playPrevTrack     : function ()
		{
			var t = this,
				tracks = t.playlist.find( 'li' ),
				current = tracks.filter( '.current' ),
				played = tracks.filter( '.played' ).not( '.current' ),
				prev;
			if ( played.length < 1 )
			{
				current.removeClass( 'played' );
				played = tracks.not( '.current' );
			}
			if ( t.options.shuffle )
			{
				var random = Math.floor( Math.random() * played.length );
				prev = played.eq( random );
			}
			else
			{
				prev = current.prev();
				if ( prev.length < 1 && t.options.loop )
				{
					prev = current.siblings().last();
				}
			}
			if ( prev.length == 1 )
			{
				t.options.beforePrev( t );

				current.removeClass( 'played' );
				t.playTrack( prev );

				t.options.afterPrev( t );
			}
		},
		playTrack         : function ( track )
		{
			var t = this;

			t.setCurrentTrack( track );
			t.updateTrackTitle();
			t.updateTrackRating();

			t.pause();
			t.setSrc( track.data( 'url' ) );
			t.load();
			t.play();

			// Set track info
			$( '#track-info-' + track.data( 'id' ) ).addClass( 'current' ).siblings().removeClass( 'current' );
		},
		setCurrentTrack: function( track )
		{
			if ( typeof track === 'undefined' )
			{
				this.currentTrack = this.playlist.children( 'li:first' ).addClass( 'current' ).siblings().removeClass( 'current' );
				return;
			}

			this.currentTrack = track;
			track.addClass( 'current' ).siblings().removeClass( 'current' );
		}
	} );
})( mejs.$ );
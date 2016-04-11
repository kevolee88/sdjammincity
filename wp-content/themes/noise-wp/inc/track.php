<?php
/**
 * Get track info
 *
 * @since  1.0
 * @param  object $track
 * @return string
 */
function noise_get_track_info( $track = null )
{
	global $post;
	$track = empty( $track ) ? $post : $track;

	if ( is_numeric( $track ) )
		$track = get_post( $track );

	$voted = isset( $_COOKIE['_noise_vote_' . $track->ID] );
	$score = noise_get_meta( 'votes_num', '', $track->ID ) ? round( noise_get_meta( 'votes', '', $track->ID ) / noise_get_meta( 'votes_num', '', $track->ID ) ) : min( noise_get_meta( 'votes', '', $track->ID ), 5 );
	$rating = '';
	for( $i = 1; $i <= 5; $i++ )
		$rating .= sprintf(
			'<span class="%s heart"></span>',
			$i <= $score ? 'entypo-heart' : 'entypo-heart2'
		);

	$file = noise_get_meta( 'upload', 'type=file_input', $track->ID );

	// Get track information
	$info = '<div id="track-info-' . $track->ID . '" class="track-info clearfix">';

	// Artist info (left)
	if ( fitwp_option( 'extended_player_img' ) == 'album_image' )
	{
		$cover_extended =  noise_get_meta( 'album', '', $track->ID );
	}
	else
	{
		$cover_extended =  noise_get_meta( 'artist', '', $track->ID );
	}
	if ( $cover_extended )
	{
		$info .= '<div class="artist-info left">';

		// Thumbnail
		$info .= noise_get_image( array(
			'post_id'  => $cover_extended,
			'size'     => 'player-expanded',
			'meta_key' => 'images',
			'default'  => '<img src="http://placehold.it/240x280">',
			'echo'     => false,
			'attr'     => array(
				'class' => 'artist-image',
				'title' => esc_attr( get_the_title( $cover_extended ) ),
			),
		) );

		// Social links
		$info .= '<div class="links socials">';
		$socials = array( 'facebook', 'twitter', 'soundcloud', 'google_plus', 'vimeo' );
		foreach ( $socials as $social )
		{
			$icon = str_replace( '_', '', $social );
			if ( $link = esc_url( noise_get_meta( $social, '', $cover_extended ) ) )
				$info .= "<a href='$link' class='entypo-$icon' rel='nofollow' target='_blank'></a>";
		}
		$info .= '</div>'; // .links.socials

		$info .= '</div>'; // .artist-info
	}

	// Track summary
	$info .= '<div class="track-summary clearfix right">';

	// Track details
	$info .= '<div class="track-details clearfix">';
	$info .= '<div class="left">';

	$album = noise_get_meta( 'album', '', $track->ID );
	$artist = noise_get_meta( 'artist', '', $track->ID );
	$info .= '<div class="track-detail track-album">';
	$info .= '<span class="title">' . __( 'Album Name :', 'noise' ) . '</span><span class="detail">' . ( $album ? get_the_title( $album ) : '' ) . '</span>';
	$info .= '</div>';

	$info .= '<div class="track-detail track-artist">';
	$info .= '<span class="title">' . __( 'Artist Name :', 'noise' ) . '</span><span class="detail">' . get_the_title( $artist ) . '</span>';
	$info .= '</div>';

	$info .= '<div class="track-detail track-name">';
	$info .='<span class="title">' . __( 'Track Name :', 'noise' ) . '</span><span class="detail">' . $track->post_title . '</span>';
	$info .= '</div>';

	$info .= '</div>'; // .left

	$info .= '<div class="right">';

	$info .= '<span class="player-playshuffle entypo-shuffle"></span>';
	$info .= '<span class="player-playloop entypo-loop"></span>';

	$info .= '<div class="track-rating vote-ui track-vote-' . $track->ID . ' ' . ( $voted ? 'track-voted' : '' ) . '" data-id="' . $track->ID . '" data-nonce="' . wp_create_nonce( 'vote' . $track->ID ) . '">';
	$info .= $rating;
	$info .= '</div>'; // .track-rating

	$info .= '</div>';

	$info .= '</div>'; // .track-details

	$info .= '<div class="track-description clear"><div class="scroll-pane">' . $track->post_content . '</div></div>';

	$info .= '<div class="track-links-extended">'; //  .track-links-extended

	$info .= '<div class="links left">';
	if ( noise_get_meta( 'show_download', '', $track->ID ) == 1)
		$info .= '<a href="' . $file . '" target="_blank" class="entypo-download"></a>';
	$images =  noise_get_meta( 'images', '', $track->ID );
	if ( !empty( $images ) )
		$info .= '<a href="#" class="open-gallery entypo-pictures" data-id="' . $track->ID . '"></a>';
	if ( $soundcloud = noise_get_meta( 'soundcloud', '', $track->ID ) )
		$info .= '<a href="' . esc_url( $soundcloud ) . '" class="open-soundcloud entypo-soundcloud"></a>';
	$videos = noise_get_meta( 'videos', 'type=file_input', $track->ID );
	if ( !empty( $videos ) )
		$info .= '<a href="#" class="open-videos entypo-video" data-id="' . $track->ID . '"></a>';
	$info .= '</div>'; // .links

	if ( fitwp_option( 'extended_player' ) )
	{
		$info .= '<div class="go-extended right">';
		$link = add_query_arg( array(
			'action' => 'noise_get_extended_player',
			'id'     => $track->ID,
			'nonce'  => wp_create_nonce( 'get-extended-player' ),
		), admin_url( 'admin-ajax.php' ) );
		$info .= '<span class="more-extended">'  . __( 'Read more on extended player', 'noise' ) . '</span>';
		$info .= '<a href="' . $link . '" class="colorbox"></a>';
		$info .= '</div>'; // .go-extended
	}

	$info .= '</div>'; // .track-links-extended

	$info .= '</div>'; // .track-summary

	$info .= '</div>'; // .track-info

	return $info;
}

/**
 * Get track detail
 *
 * @since 1.0
 * @param  int|object $track
 * @return string
 */
function noise_get_track_detail( $track = null )
{
	global $post;
	$track = empty( $track ) ? $post : $track;

	if ( is_numeric( $track ) )
		$track = get_post( $track );

	$album = noise_get_meta( 'album', '', $track->ID );
	$artist = noise_get_meta( 'artist', '', $track->ID );
	$artist_name = get_the_title( $artist );

	$voted = isset( $_COOKIE['_noise_vote_' . $track->ID] );
	$score = noise_get_meta( 'votes_num', '', $track->ID ) ? round( noise_get_meta( 'votes', '', $track->ID ) / noise_get_meta( 'votes_num', '', $track->ID ) ) : min( noise_get_meta( 'votes', '', $track->ID ), 5 );
	$rating = '';
	for( $i = 1; $i <= 5; $i++ )
		$rating .= sprintf(
			'<span class="%s heart"></span>',
			$i <= $score ? 'entypo-heart' : 'entypo-heart2'
		);
	$image = noise_get_meta( 'cover', 'type=image_advanced', $track->ID );

	if ( empty( $image ) )
	{
		$image = noise_get_image( array(
			'post_id'   => $track->ID,
			'size'      => 'full',
			'format'    => 'src',
			'meta_key'  => 'images',
			'thumbnail' => false,
			'default'   => 'http://placehold.it/530x250',
			'echo'      => false,
		) );
	}
	else
	{
		$image = array_shift( $image );
		$image = $image['full_url'];
	}

	$detail = '<div id="track-detail-' . $track->ID . '" class="track-detail clearfix">';

	// Socail and rating
	$detail .= '<div class="track-meta left">';

	$detail .= '<div class="image detail-image" style="background-image: url(' . $image . ')">';
	$detail .= '<div class="caption"><a href="#" class="text" data-id="' . $track->ID . '">
			<span class="big">' . $track->post_title . '</span>
			<span class="small">' . $artist_name . '</span></a></div>';
	$detail .= '</div>'; // end .image

	$detail .= '<div class="track-data">';
	$detail .= '<p class="album-name"><span class="label">' . __( 'Album Name', 'noise' ) . ':</span><span>' . get_the_title( $album ) . '</span></p>';
	$detail .= '<p class="release-date"><span class="label">' . __( 'Release Date', 'noise' ) . ':</span><span>' . noise_get_meta( 'date', '', $track->ID ) . '</span></p>';
	$detail .= '<p class="artist-name"><span class="label">' . __( 'Artist', 'noise' ) . ':</span><span>' . $artist_name . '</span></p>';
	$detail .= '<p class="length"><span class="label">' . __( 'Length', 'noise' ) . ':</span><span>' . noise_get_meta( 'length', '', $track->ID ) . '</span></p>';
	$detail .= '<p class="composer"><span class="label">' . __( 'Composer', 'noise' ) . ':</span><span>' . noise_get_meta( 'composer', '', $track->ID ) . '</span></p>';
	$detail .= '<p class="genre"><span class="label">' . __( 'Genre', 'noise' ) . ':</span><span>' . noise_get_meta( 'genre', '', $track->ID ) . '</span></p>';
	$detail .= '</div>'; // End .title-artist

	$detail .= '<div class="track-rating vote-ui track-vote-' . $track->ID . ' ' . ( $voted ? 'track-voted' : '' ) . '" data-id="' . $track->ID . '" data-nonce="' . wp_create_nonce( 'vote' . $track->ID ) . '">';
	$detail .= $rating;
	$detail .= '<p>';
	$detail .= '<span class="views">' . intval( noise_get_meta( 'views', '', $track->ID ) ) . ' ' . __( 'Views', 'noise' ) . '</span>';
	$detail .= '<span class="votes">' . intval( noise_get_meta( 'votes_num', '', $track->ID ) ) . ' ' . __( 'Votes', 'noise' ) . '</span>';
	$detail .= '</p>';
	$detail .= '</div>';

	$detail .= '<div class="links actions socials">';
	$detail .= '<span class="button"><i class="entypo-share"></i>' . __( 'Share', 'noise' ) . '</span>';
	$detail .= '<a class="entypo-facebook simple-share button" href="http://www.facebook.com/sharer/sharer.php?u=' . urlencode( get_permalink( $track->ID ) ) . '&title=' . urlencode( $track->post_title ) . '" target="_blank"></a>';
	$detail .= '<a class="entypo-twitter simple-share button" href="http://twitter.com/intent/tweet?url=' . urlencode( get_permalink( $track->ID ) ) . '&text=' . urlencode( $track->post_title ) . '" target="_blank"></a>';
	$detail .= '<a class="entypo-googleplus simple-share button" href="https://plus.google.com/share?url=' . urlencode( get_permalink( $track->ID ) ) . '" target="_blank"></a>';
	$detail .= '</div>'; // End .links.actions

	$detail .= '</div>'; // End .social-rating

	// lyric
	$lyric = noise_get_meta( 'lyrics', '', $track->ID );
	$lyric = empty( $lyric ) ? __( 'Lyric is updating', 'noise' ) : $lyric;

	$detail .= '<div id="track-lyric-' . $track->ID . '" class="track-lyric right">';
	$detail .= '<p class="label">' . __( 'Lyric', 'noise' ) . '</span>';
	$detail .= '<div class="scroll-pane top-scroll-bar">' . wpautop( $lyric ) . '</div>';
	$detail .= '</div>'; // end .track-lyric

	$detail .= '</div>'; // end .track-detail

	return $detail;
}

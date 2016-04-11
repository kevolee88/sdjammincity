<?php
// Build playlist
$tracks = get_posts( array(
	'post_type'      => 'track',
	'posts_per_page' => fitwp_option( 'latest_tracks_num' ),
	'post_status'    => 'publish',
) );
if ( empty( $tracks ) )
	return;

$intro = fitwp_option( 'intro_audio' );
$intro_track = fitwp_option( 'intro_track' );

if ( $intro && $intro_track )
{
	$intro_track = get_post( $intro_track );
	$new_order = array();
	if ( in_array( $intro_track, $tracks ) )
	{
		$key = array_search( $intro_track, $tracks );
		unset( $tracks[$key] );
		$tracks = array_merge( array( $intro_track ), $tracks );
	}
}

$first = '';
$playlist = '<ul id="mejs-playlist" class="hidden">';
$tracks_info = '<div id="tracks-info" class="hidden tracks-info">';
$tracks_detail = '<div id="tracks-detail" class="hidden">';
foreach ( $tracks as $track )
{
	// Get track media file to play
	$file = noise_get_meta( 'upload', 'type=file_input', $track->ID );
	if ( empty( $file ) )
		continue;

	$rate = noise_get_meta( 'votes_num', '', $track->ID ) ? round( noise_get_meta( 'votes', '', $track->ID ) / noise_get_meta( 'votes_num', '', $track->ID ) ) : min( noise_get_meta( 'votes', '', $track->ID ), 5 );
	$playlist .= sprintf(
		'<li data-url="%1$s" title="%2$s" data-id="%3$s" data-nonce="%4$s" data-rate="%5$s">%2$s</li>',
		esc_url( $file ),
		esc_attr( $track->post_title ),
		$track->ID,
		isset( $_COOKIE['_noise_vote_' . $track->ID] ) ? '' : wp_create_nonce( 'vote' . $track->ID ),
		$rate
	);

	if ( !$first )
		$first = $file;

	$info = noise_get_track_info( $track );
	$tracks_info .= $info;
	$tracks_detail .= noise_get_track_detail( $track );
}
$playlist .= '</ul>';
$tracks_info .= '</div>';
$tracks_detail .= '</div>';
$ext = wp_check_filetype( $first );
?>
<section id="section-player" class="section-player section">
	<div class="inner">
		<audio id="player" controls="controls" width="100%" preload="none">
			<source type="<?php echo $ext['type'] ?>" src="<?php echo $first; ?>" />
		</audio>
		<?php echo $tracks_info; ?>
		<?php echo $tracks_detail; ?>
		<?php echo $playlist; ?>
	</div>
</section>

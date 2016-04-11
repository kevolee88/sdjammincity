<?php
$title = noise_kses( fitwp_option( 'counting_event_title' ) );
$desc  = noise_kses( fitwp_option( 'counting_event_desc' ) );
$end   = noise_kses( fitwp_option( 'counting_end' ) );
$type  = fitwp_option( 'countdown_bg_type' );
?>
<section id="section-countdown" class="section-countdown section <?php echo $type; ?>">
	<?php
	$detect = new Mobile_Detect;
	if ( 'video' == $type && !$detect->isMobile() )
	{
		$video = fitwp_option( 'countdown_video' );
		$ext = wp_check_filetype( $video );
		if ( empty( $ext['ext'] ) )
		{
			parse_str( parse_url( $video, PHP_URL_QUERY ), $video_args );
			echo '<div id="countdown-youtube-bg" data-video="' . $video_args['v'] . '"></div>';
		}
		else
		{
			$video_alt      = fitwp_option( 'countdown_video_alt' );
			$video_alt_type = wp_check_filetype( $video_alt );
			$alt            = !empty( $video_alt_type['ext'] ) ? sprintf( '<source type="%s" src="%s" />', $video_alt_type['type'], $video_alt ) : '';
			printf(
				'<div class="parallax"><video id="countdown-video-bg" preload="auto" autoplay loop muted><source type="%s" src="%s" />%s</video></div>',
				$ext['type'],
				$video,
				$alt
			);
		}
	}
	else
	{
		echo '<div class="parallax"></div>';
	}
	?>

	<div class="section-mark"></div>
	<div class="counting-event">
		<h2 class="event-title inner"><?php echo $title; ?></h2>
		<p class="event-desc inner"><?php echo $desc; ?></p>
	</div>
	<div class="countdown-wrap inner">
		<span id="countdown" class="countdown" data-end="<?php echo date( 'F d, Y H:i:s', strtotime( $end ) ); ?>">
			<span class="count-month counter">00</span>
			<span class="count-day counter">00</span>
			<span class="count-hour counter">00</span>
			<span class="count-minute counter">00</span>
			<span class="count-second counter">00</span>
		</span>
		<span class="countdown-labels">
			<span><?php _e( 'Months', 'noise' ); ?></span>
			<span><?php _e( 'Days', 'noise' ); ?></span>
			<span><?php _e( 'Hours', 'noise' ); ?></span>
			<span><?php _e( 'Minutes', 'noise' ); ?></span>
			<span><?php _e( 'Seconds ', 'noise' ); ?></span>
		</span>
	</div>
</section>
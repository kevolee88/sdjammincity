<?php
$title  = noise_kses( fitwp_option( 'latest_track_title' ) );
$suffix = noise_kses( fitwp_option( 'latest_track_suffix' ) );
$desc   = noise_kses( fitwp_option( 'latest_track_subtitle' ) );

$tracks = new WP_Query( array(
	'post_type'      => 'track',
	'posts_per_page' => intval( fitwp_option( 'latest_tracks_num' ) ),
	'post__not_in'   => get_option( 'latest_tracks_exclude', array() ),
) );
?>

<section id="section-latest-tracks" class="section-latest-tracks section">
	<div class="inner">
		<h2 class="section-title">
			<?php
			printf(
				'<span>%s</span>%s%s',
				$title,
				$suffix ? '<span class="suffix">' . $suffix . '</span>' : '',
				$desc ? '<span class="desc">' . $desc . '</span>' : ''
			);
			?>
		</h2>

		<?php if ( $tracks->have_posts() ) : ?>
		<div id="latest-tracks-slider" class="latest-tracks-slider media-slider vertical-slider tiny-carousel">
			<div id="latest-tracks-nav" class="flex-direction-nav">
				<a class="flex-prev prev"></a>
				<a class="flex-next next"></a>
			</div>
			<div class="viewport">
				<ul  class="slides overview">
					<?php
					while( $tracks->have_posts() ) : $tracks->the_post();
						// Get track media file to play
						$file  = noise_get_meta( 'upload', 'type=file_input' );
						$voted = isset( $_COOKIE['_noise_vote_' . get_the_ID()] );
						$rate  = noise_get_meta( 'votes_num', '' ) ? round( noise_get_meta( 'votes', '' ) / noise_get_meta( 'votes_num', '' ) ) : min( noise_get_meta( 'votes', '' ), 5 );
					?>

						<?php echo ( $tracks->current_post % 5 == 0 ) ? '<li>' : ''; ?>

						<div <?php post_class() ?>>
							<?php noise_get_image( 'size=post-thumbnail' ) ?>
							<div class="overlay"></div>
							<h3 class="track-title" title="<?php the_title(); ?>"><?php echo noise_title_limit( 24 ); ?></h3>
							<a href="#" class="play-track entypo-play" data-id="<?php the_ID() ?>" data-url="<?php echo esc_attr( $file ); ?>" data-rate="<?php echo $rate ?>" data-nonce="<?php echo $voted ? '' : wp_create_nonce( 'vote' . get_the_ID() ) ?>" data-title="<?php the_title_attribute(); ?>" title="<?php echo __( 'Play', 'noise' ) . ' ' . the_title_attribute( 'echo=0' ); ?>"></a>
						</div>

						<?php echo ( $tracks->current_post % 5 == 4 || $tracks->post_count == $tracks->current_post + 1 ) ? '</li>' : ''; ?>

					<?php
					endwhile;
					wp_reset_postdata();
					?>
				</ul>

			</div>
		</div><!-- end .latest-tracks-slider -->
		<?php endif; ?>
	</div>
</section>

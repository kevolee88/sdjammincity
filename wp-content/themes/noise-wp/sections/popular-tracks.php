<?php
$title  = noise_kses( fitwp_option( 'popular_tracks_title' ) );
$suffix = noise_kses( fitwp_option( 'popular_tracks_suffix' ) );
$desc   = noise_kses( fitwp_option( 'popular_tracks_subtitle' ) );
$numb   = intval( fitwp_option( 'popular_tracks_num' ) );

// Top 3 of week
$top_week = new WP_Query( array(
	'post_type'      => 'track',
	'posts_per_page' => $numb,
	'orderby'        => 'meta_value_num',
	'meta_key'       => 'votes_week',
) );

// Top 3 of month
$top_month = new WP_Query( array(
	'post_type'      => 'track',
	'posts_per_page' => $numb,
	'orderby'        => 'meta_value_num',
	'meta_key'       => 'votes_month',
) );
?>

<section id="section-popular-tracks" class="section-popular-tracks section">
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

		<div class="top-week popular-tracks">
			<div class="tracks">
				<?php while( $top_week->have_posts() ) : $top_week->the_post(); ?>
				<?php $file = noise_get_meta( 'upload', 'type=file_input' ); ?>

				<div <?php post_class(); ?>>
					<div class="track-no"><?php echo $top_week->current_post + 1; ?></div>
					<div class="track-title">
						<h3 title="<?php the_title() ?>"><?php echo noise_title_limit( 20 ) ?></h3>
						<span class="artist-name"><?php echo get_the_title( noise_get_meta( 'artist' ) ) ?></span>
					</div>
					<div class="track-image">
						<?php noise_get_image( 'size=track-thumbnail' ) ?>
						<a href="javascript:;" class="play-track entypo-play" data-id="<?php the_ID() ?>" data-url="<?php echo esc_url( $file ); ?>" data-title="<?php the_title_attribute(); ?>" title="<?php echo __( 'Play', 'noise' ) . ' ' . the_title_attribute( 'echo=0' ); ?>"></a>
					</div>
					<div class="week-vote track-vote">
						<i class="icon entypo-heart"></i>
						<span><?php echo intval( noise_get_meta( 'votes_week' ) ) ?></span>
					</div>
				</div>

				<?php endwhile; ?>
			</div>

			<div class="block-title">
				<?php echo __( 'Top ', 'noise' ) . $numb ; ?>
				<span class="suffix"><?php _e( 'of week', 'noise' ) ?></span>
			</div>
		</div><!-- end .top-week -->

		<div class="top-month popular-tracks last">
			<div class="tracks">
				<?php while( $top_month->have_posts() ) : $top_month->the_post(); ?>
				<?php $file = noise_get_meta( 'upload', 'type=file_input' ); ?>

				<div <?php post_class(); ?>>
					<div class="track-no"><?php echo $top_month->current_post + 1; ?></div>
					<div class="track-title">
						<h3 title="<?php the_title() ?>"><?php echo noise_title_limit( 20 ) ?></h3>
						<span class="artist-name"><?php echo get_the_title( noise_get_meta( 'artist' ) ) ?></span>
					</div>
					<div class="track-image">
						<?php noise_get_image( 'size=track-thumbnail' ) ?>
						<a href="javascript:;" class="play-track entypo-play" data-id="<?php the_ID() ?>" data-url="<?php echo esc_url( $file ); ?>" data-title="<?php the_title_attribute(); ?>" title="<?php echo __( 'Play', 'noise' ) . ' ' . the_title_attribute( 'echo=0' ); ?>"></a>
					</div>
					<div class="week-vote track-vote">
						<i class="icon entypo-heart"></i>
						<span><?php echo intval( noise_get_meta( 'votes_month' ) ) ?></span>
					</div>
				</div>

				<?php endwhile; ?>
			</div>

			<div class="block-title">
				<?php echo __( 'Top ', 'noise' ) . $numb; ?>
				<span class="suffix"><?php _e( 'of month', 'noise' ) ?></span>
			</div>
		</div><!-- end .top-week -->
	</div>
</section>
<?php wp_reset_postdata(); ?>

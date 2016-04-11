<?php
$title  = noise_kses( fitwp_option( 'media_title' ) );
$suffix = noise_kses( fitwp_option( 'media_suffix' ) );
$desc   = noise_kses( fitwp_option( 'media_subtitle' ) );

$images = new WP_Query( array(
	'post_type'      => 'gallery',
	'posts_per_page' => intval( fitwp_option( 'media_photo_num' ) ),
	'meta_key'       => 'type',
	'meta_value'     => 'photos',
) );

$videos = new WP_Query( array(
	'post_type'      => 'gallery',
	'posts_per_page' => intval( fitwp_option( 'media_video_num' ) ),
	'meta_key'       => 'type',
	'meta_value'     => 'videos',
) );
?>

<section id="section-media" class="section-media section tabs-title">
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

		<div class="tabs simple-tabs">
			<ul class="tabs-nav">
				<li><a href="#media-photos" class="active"><?php _e( 'Photos', 'noise' ) ?></a> /</li>
				<li><a href="#media-videos"><?php _e( 'Videos', 'noise' ) ?></a></li>
			</ul>

			<div id="media-photos" class="open tab-panel">
				<?php if ( $images->have_posts() ) : ?>
				<div id="media-photos-slider" class="media-slider vertical-slider tiny-carousel">
					<div class="viewport">
						<ul class="slides overview">
							<?php
							while( $images->have_posts() ) : $images->the_post();
								$files = noise_get_meta( 'images', 'type=image_advanced' );
							?>

								<?php echo ( $images->current_post % 5 == 0 ) ? '<li>' : ''; ?>

								<div <?php post_class() ?>>
									<?php noise_get_image( 'size=post-thumbnail' ) ?>
									<div class="overlay"></div>
									<h3 title="<?php the_title(); ?>"><?php echo noise_title_limit( 20 ); ?> : <span class="media-count"><?php echo count( $files ); ?></span></h3>
									<a href="javascript:;" class="open-gallery view-media entypo-eye" data-id="<?php the_ID() ?>"></a>
								</div>

								<?php echo ( $images->current_post % 5 == 4 || $images->post_count == $images->current_post + 1 ) ? '</li>' : ''; ?>

							<?php
							endwhile;
							wp_reset_postdata();
							?>
						</ul>
					</div>
					<div id="media-photos-nav" class="flex-direction-nav">
						<a class="flex-prev prev"></a>
						<a class="flex-next next"></a>
					</div>
				</div>
				<?php endif; ?>
			</div><!-- end #media-photos -->

			<div id="media-videos" class="tab-panel media-slider vertical-slider slider">
				<?php if ( $videos->have_posts() ) : ?>
				<div id="media-videos-slider" class="media-slider vertical-slider tiny-carousel">
					<div class="viewport">
						<ul class="slides overview">
							<?php
							while( $videos->have_posts() ) : $videos->the_post();
								$files = noise_get_meta( 'videos', 'type=file_input' );
							?>

								<?php echo ( $videos->current_post % 5 == 0 ) ? '<li>' : ''; ?>

								<div <?php post_class() ?>>
									<?php noise_get_image( 'size=post-thumbnail' ) ?>
									<div class="overlay"></div>
									<h3 title="<?php the_title(); ?>"><?php echo noise_title_limit( 20 ); ?> : <span class="media-count"><?php echo count( $files ); ?></span></h3>
									<a href="javascript:;" class="open-videos view-media entypo-play" data-id="<?php the_ID() ?>"></a>
								</div>

								<?php echo ( $videos->current_post % 5 == 4 || $videos->post_count == $videos->current_post + 1 ) ? '</li>' : ''; ?>

							<?php
							endwhile;
							wp_reset_postdata();
							?>
						</ul>
					</div>
					<div id="media-videos-nav" class="flex-direction-nav">
						<a class="flex-prev prev"></a>
						<a class="flex-next next"></a>
					</div>
				</div>
				<?php endif; ?>
			</div><!-- end #media-photos -->
		</div><!-- end .tabs -->
	</div>
</section>

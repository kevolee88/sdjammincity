<?php
$display = fitwp_option( 'artists_display' );
$artist_class = '';
$query = array(
	'post_type' => 'artist'
);

if ( 'featured' == $display )
{
	$query['meta_key']       = '_featured_artist';
	$query['meta_value']     = '1';
	$query['posts_per_page'] = -1;
}
else
{
	$query['posts_per_page'] = intval( fitwp_option( 'artists_num' ) );
}

$artists = new WP_Query( $query );

if( $artists->post_count == 1 )
{
	$artist_class = 'artist-single';
	$artist_id = 0;
}
?>

<section id="section-artists" class="section-artists section">
	<div class="inner">
		<h2 class="section-title">
			<?php
			$title  = noise_kses( fitwp_option( 'artist_title' ) );
			$suffix = noise_kses( fitwp_option( 'artist_suffix' ) );
			$desc   = noise_kses( fitwp_option( 'artist_subtitle' ) );
			printf(
				'<span>%s</span>%s%s',
				$title,
				$suffix ? '<span class="suffix">' . $suffix . '</span>' : '',
				$desc ? '<span class="desc">' . $desc . '</span>' : ''
			);
			?>
		</h2>

		<div id="artists-slider" class="artists-slider vertical-slider slider <?php echo $artist_class; ?>">
		<?php if ( $artists->have_posts() ) : ?>
			<ul class="slides">
			<?php while( $artists->have_posts() ) : $artists->the_post(); ?>
				<?php $artist_id = get_the_ID(); ?>
				<?php echo ( $artists->current_post % 3 == 0 ) ? '<li>' : ''; ?>

				<div <?php post_class(); ?> data-id="<?php the_ID(); ?>">
					<div class="artist-thumbnail">
						<?php noise_get_image( 'size=artist-thumbnail' ); ?>
						<div class="overlay"></div>
						<h3 class="artist-name" title="<?php the_title(); ?>"><?php echo noise_title_limit( 24 ); ?></h3>
						<a href="javascript:;" class="view-artist entypo-info" data-id="<?php the_ID(); ?>"></a>
						<a href="javascript:;" class="hidden artist-gallery-link">
							<?php echo count( noise_get_meta( 'images', 'type=image_advanced' ) ); ?>
						</a>
					</div>
					<div class="artist-info">
						<div class="quick-facts">
							<p class="info-title"><?php _e( 'Quick Facts', 'noise' ) ?></p>
							<?php
							$full_name = noise_get_meta( 'full_name' );
							$nick_name = noise_get_meta( 'nick_name' );
							$date_of_birth = noise_get_meta( 'date_of_birth' );
							$place_of_birth = noise_get_meta( 'place_of_birth' );
							$occupation = noise_get_meta( 'occupation' );
							$best_known = noise_get_meta( 'best_known' );
							$synopsis = noise_get_meta( 'synopsis' );
							if ( !empty( $full_name ) )
								printf(
									'<span class="full-name">%s: <span>%s</span></span>',
									__( 'Full Name', 'noise' ),
									$full_name
								);
							if ( !empty( $nick_name ) )
								printf(
									'<span class="nick-name">%s: <span>%s</span></span>',
									__( 'Nick Name', 'noise' ),
									$nick_name
								);
							if ( !empty( $date_of_birth ) )
								printf(
									'<span class="birth-date">%s: <span>%s</span></span>',
									__( 'Birth Date', 'noise' ),
									$date_of_birth
								);
							if ( !empty( $place_of_birth ) )
								printf(
									'<span class="birth-place">%s: <span>%s</span></span>',
									__( 'Place Of Birth', 'noise' ),
									$place_of_birth
								);
							if ( !empty( $occupation ) )
								printf(
									'<span class="occupation">%s: <span>%s</span></span>',
									__( 'Occupation', 'noise' ),
									$occupation
								);
							?>
						</div>

						<?php if ( !empty( $best_known ) ) : ?>
							<div class="best-known-for">
								<p class="info-title"><?php _e( 'Best Known For', 'noise' ) ?></p>
								<div class="custom-scroll-nav right">
									<span class="scroll-up entypo-arrow-up4"></span>
									<span class="scroll-down entypo-arrow-down5"></span>
								</div>
								<div class="scroll-pane top-scroll-bar"><?php echo wpautop( $best_known ) ?></div>
							</div>
						<?php endif;?>

						<?php if ( !empty( $synopsis ) ) : ?>
							<div class="synopsis">
								<p class="info-title"><?php _e( 'Synopsis', 'noise' ) ?></p>
								<div class="custom-scroll-nav right">
									<span class="scroll-up entypo-arrow-up4"></span>
									<span class="scroll-down entypo-arrow-down5"></span>
								</div>
								<div class="scroll-pane top-scroll-bar"><?php echo wpautop( $synopsis ) ?></div>
							</div>
						<?php endif;?>

						<div class="artist-socials socials">
							<?php
							$socials = array( 'facebook', 'twitter', 'soundcloud', 'google_plus', 'vimeo', 'homepage_artist' );
							foreach( $socials as $social )
							{
								$url = noise_get_meta( $social );
								if ( !empty( $url ) )
									printf(
										'<a href="%s" class="entypo-%s" target="_blank">%s</a>',
										esc_url( $url ),
										str_replace( '_', '', $social ),
										ucwords( str_replace( '_', ' ', $social ) )
									);
							}
							?>
						</div>
					</div><!-- end .artist-info -->
				</div><!-- end post_class() -->

				<?php echo ( $artists->current_post % 3 == 2 || $artists->post_count == $artists->current_post + 1 ) ? '</li>' : ''; ?>
			<?php endwhile; ?>
			</ul>

			<div class="view-artist-gallery open-gallery entypo-pictures" <?php echo $artists->post_count == 1 ? 'data-id="' . $artist_id . '"' : '' ?>></div>
			<div class="hide-detail entypo-cross"></div>
			<div class="artist-direction-nav">
				<a class="artist-prev disable" href="#"><?php _e( 'Previous', 'noise' ) ?></a>
				<a class="artist-next" href="#"><?php _e( 'Next', 'noise' ) ?></a>
			</div>
		<?php endif; ?>
		</div><!-- end .artists-slider -->

	</div><!-- end .inner -->
</section>
<?php wp_reset_postdata(); ?>

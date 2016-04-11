<?php
$title  = noise_kses( fitwp_option( 'releases_title' ) );
$suffix = noise_kses( fitwp_option( 'releases_suffix' ) );
$desc   = noise_kses( fitwp_option( 'releases_subtitle' ) );

$albums = new WP_Query( array(
	'post_type'      => 'album',
	'posts_per_page' => intval( fitwp_option( 'releases_num' ) ),
) );
?>

<section id="section-releases" class="section-releases section">
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

		<div id="releases" class="releases-slider vertical-slider slider">
			<ul class="slides">
			<?php while( $albums->have_posts() ) : $albums->the_post(); ?>
				<?php
				$tracks = get_posts( array(
					'post_type'  => 'track',
					'posts_per_page'  => -1,
					'meta_key'   => 'album',
					'meta_value' => get_the_ID(),
				) );
				?>
				<li>
					<div class="album-cover">
						<?php noise_get_image( 'size=album-thumbnail' ) ?></a>
						<div class="overlay"></div>
						<a href="<?php the_permalink(); ?>" class="view-detail entypo-info"></a>
						<?php if ( !empty( $tracks ) ) : ?>
							<a href="#" class="play-album entypo-play" data-id="<?php the_ID(); ?>"></a>
						<?php endif; ?>
						<div class="short-info">
							<span class="album-title"><?php the_title(); ?></span>
							<span class="num-tracks">
								<?php _e( 'Number of tracks', 'noise' ) ?> :
								<span><?php echo count( $tracks ) ?></span>
							</span>
						</div>
					</div>
					<div <?php post_class() ?>>

						<div class="album-info">
							<?php noise_get_image( 'size=album-preview' ) ?>

							<div class="info">
								<p class="album-name">
									<span class="label"><?php _e( 'Album Name', 'noise' ) ?>:</span>
									<span><?php the_title(); ?></span>
								</p>

								<p class="album-composer">
									<span class="label"><?php _e( 'Composer', 'noise' ) ?>:</span>
									<span><?php echo noise_get_meta( 'composer' ); ?></span>
								</p>

								<p class="album-date">
									<span class="label"><?php _e( 'Release Date', 'noise' ) ?>:</span>
									<span><?php echo noise_get_meta( 'date' ); ?></span>
								</p>

								<p class="album-genre">
									<span class="label"><?php _e( 'Genre', 'noise' ) ?>:</span>
									<span><?php echo noise_get_meta( 'genre' ); ?></span>
								</p>
							</div>

							<?php
							$sources = array(
								'itunes'  => esc_url( noise_get_meta( 'itunes' ) ),
								'amazon'  => esc_url( noise_get_meta( 'amazon' ) ),
								'spotify' => esc_url( noise_get_meta( 'spotify' ) ),
							);
							$sources = array_filter( $sources );
							?>

							<?php if ( ! empty( $sources ) ) : ?>
							<div class="album-sources">
								<span class="button"><i class="entypo-cart"></i><?php _e( 'Buy Now', 'noise' ) ?></span>
								<?php
								$last = end( $sources );
								foreach( $sources as $source => $url )
								{
									printf(
										'<a href="%s" class="%s %s album-source button" target="_blank">%s</a>',
										$url,
										$url == $last ? 'last' : '',
										$source,
										ucwords( $source )
									);
								}
								?>
							</div>
							<?php endif; ?>
						</div>

						<div class="album-tracks">
							<?php if ( noise_get_meta( 'realse_review' ) ) : ?>

							<p class="label"><?php _e( 'Review', 'noise' ); ?></p>
							<div class="album-review">
								<div class="scroll-pane top-scroll-bar"><?php echo noise_get_meta( 'realse_review' ) ?></div>
							</div>

							<?php endif; ?>

							<p class="label"><?php _e( 'Tracklist', 'noise' ) ?></p>
							<div class="tracklist">
								<div class="scroll-pane top-scroll-bar">
									<ol>
									<?php if ( ! empty( $tracks ) ) : ?>
										<?php foreach( $tracks as $track ) : ?>

										<?php
										$voted = isset( $_COOKIE['_noise_vote_' . $track->ID] );
										$rate = noise_get_meta( 'votes_num', '', $track->ID ) ? round( noise_get_meta( 'votes', '', $track->ID ) / noise_get_meta( 'votes_num', '', $track->ID ) ) : min( noise_get_meta( 'votes', '', $track->ID ), 5 );
										?>
										<li>
											<p class="track-name" title="<?php the_title(); ?>">
												<?php echo noise_title_limit( 24, $track->ID ) ?>
												<span class="track-length"><?php echo noise_get_meta( 'length', '', $track->ID ); ?></span>
											</p>

											<p class="actions">
												<?php  $file = noise_get_meta( 'upload', 'type=file_input', $track->ID ); ?>
												<span class="play-track entypo-play" data-id="<?php echo $track->ID ?>" data-url="<?php echo esc_url( $file ); ?>" data-title="<?php echo esc_attr( $track->post_title ); ?>" data-rate="<?php echo $rate ?>" data-nonce="<?php echo $voted ? '' : wp_create_nonce( 'vote' . $track->ID ) ?>" title="<?php echo __( 'Play', 'noise' ) . ' ' . esc_attr( $track->post_title ); ?>"></span>
												<span class="open-gallery entypo-pictures track-gallery" data-id="<?php echo $track->ID; ?>"></span>
												<span><a class="open-soundcloud entypo-soundcloud" href="<?php echo esc_url( noise_get_meta( 'soundcloud', '', $track->ID ) ); ?>"></a></span>
												<span class="open-videos entypo-video track-videos" data-id="<?php echo $track->ID; ?>"></span>
											</p>

											<p class="vote-score">
												<?php
												$score = noise_get_meta( 'votes_num', '', $track->ID ) ? round( noise_get_meta( 'votes', '', $track->ID ) / noise_get_meta( 'votes_num', '', $track->ID ) ) : min( noise_get_meta( 'votes', '', $track->ID ), 5 );

												for( $i = 1; $i <= 5; $i++ )
													printf(
														'<span class="%s heart"></span>',
														$i <= $score ? 'entypo-heart' : 'entypo-heart2'
													);
												?>
											</p>
										</li>

										<?php endforeach; ?>

									<?php endif; ?>
									</ol>
								</div><!-- end .scroll-panel -->
							</div>
						</div>

					</div><!-- end .album -->

					<!-- <div class="album-share">
						<div class="facebook-share" data-url="<?php the_permalink(); ?>" data-text="<?php the_title_attribute(); ?>"></div>
						<div class="twitter-share" data-url="<?php the_permalink(); ?>" data-text="<?php the_title_attribute(); ?>"></div>
						<div class="google-share" data-url="<?php the_permalink(); ?>" data-text="<?php the_title_attribute(); ?>"></div>
					</div> -->
					<div class="album-share social-likes" data-url="<?php the_permalink(); ?>" data-title="<?php the_title_attribute(); ?>" data-zeroes="yes">
						<div class="facebook"></div>
						<div class="twitter"></div>
						<div class="plusone"></div>
					</div>
				</li>

			<?php endwhile; ?>
			</ul>

			<div class="hide-detail entypo-cross"></div>
		</div><!-- end .releases-slider -->

	</div>
</section>
<?php wp_reset_postdata(); ?>
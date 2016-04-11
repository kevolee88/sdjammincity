<section id="section-latest-news" class="section-latest-news section">
	<div class="inner">
		<h2 class="section-title">
			<?php
			$title  = noise_kses( fitwp_option( 'latest_news_title' ) );
			$suffix = noise_kses( fitwp_option( 'latest_news_suffix' ) );
			$desc   = noise_kses( fitwp_option( 'alatest_news_subtitle' ) );
			printf(
				'<span>%s</span>%s%s',
				$title,
				$suffix ? '<span class="suffix">' . $suffix . '</span>' : '',
				$desc ? '<span class="desc">' . $desc . '</span>' : ''
			);
			?>
		</h2>

		<div class="latest-news">
			<?php
			$readmore = fitwp_option( 'latest_news_readmore' );
			$news = new WP_Query( array( 'posts_per_page' => 3, 'ignore_sticky_posts' => true ) );
			while( $news->have_posts() ) : $news->the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header>
					<div class="entry-thumbnail">
						<?php noise_get_image( array( 'size' => 'blog-small-thumbnaill' ) ) ?>
						<div class="overlay"></div>
						<a href="<?php the_permalink(); ?>" class="view-detail"><span class="entypo-search"></span></a>
						<div class="entry-info">
							<span class="entry-format"></span>
							<span class="entry-date"><?php the_time( 'd M' ); ?></span>
						</div>
					</div>

					<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo noise_title_limit( 28 ); ?></a></h3>

					<div class="entry-meta">
						<span class="categories-links"><?php _e( 'Category', 'noise' ) ?> : <?php the_category( ', ' ); ?></span>
					</div>
				</header>

				<div class="entry-content">
					<?php noise_content_limit( 25, '' ); ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="more-link"><?php echo $readmore; ?></a>
				</div>
			</article>

			<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
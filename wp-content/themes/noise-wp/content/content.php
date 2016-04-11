<article <?php post_class() ?>>
	<header class="entry-header">
		<?php noise_get_post_thumbnail(); ?>
		<?php if ( !is_singular() || is_page_template( 'template-blog.php' ) ) : ?>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php else : ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php endif; ?>
		<div class="entry-meta">
			<span class="author-link"><?php _e( 'By', 'noise' ) ?> : <?php the_author_posts_link() ?></span>&nbsp;|
			<span class="comments-link"><?php _e( 'Comments', 'noise' ) ?> : <span><?php comments_popup_link( __( 'No comment', 'noise' ), '1', '% Comments' ); ?></span></span>&nbsp;|
			<span class="categories-links"><?php _e( 'Category', 'noise' ) ?> : <?php the_category( ', ' ); ?></span>
		</div>
	</header>
	<div class="entry-content">
		<?php
		if ( is_singular() && !is_page_template( 'template-blog.php' ) )
		{
			the_content();
			wp_link_pages();
			edit_post_link( __( 'Edit This Post', 'noise' ), '<p class="edit-link">', '</p>' );
		}
		else
		{
			$display   = fitwp_option( 'blog_display' );
			$more_text = fitwp_option( 'readmore_text' );

			switch ( $display )
			{
				case 'excerpt':
					the_excerpt();
					if ( !empty( $more_text ) )
						printf( '<a href="%s" class="read-more">%s</a>', get_permalink(), esc_html( $more_text ) );

					break;

				case 'more':
					if ( is_page_template( 'template-blog.php' ) )
					{
						global $more;
						$more = false;
					}

					the_content( $more_text );
					wp_link_pages( array(
						'before'      => '<p class="pages">' . __( 'Pages:', 'noise' ),
						'after'       => '</p>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					) );
					break;

				default:
					$limit = intval( fitwp_option( 'blog_content_limit' ) );
					$limit = $limit ? $limit : 55;
					noise_content_limit( $limit, $more_text );
					break;
			}

		}
		?>
	</div>
	<footer class="entry-info">
		<i class="entry-format"></i>
		<span class="entry-date"><?php the_time( 'd M' ); ?></span>
		<?php the_tags( '<span class="tags-links"><i class="entypo-tag"></i>', ', ', '</span>' ) ?>
	</footer>
</article>
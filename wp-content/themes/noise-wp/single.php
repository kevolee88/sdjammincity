<?php
get_header();

if ( is_singular( 'track' ) || is_singular( 'album' ) || is_singular( 'artist' ) || is_singular( 'event' ) || is_singular( 'gallery' ) ) :
	get_template_part( 'content/onepage' );
else :
	get_template_part( 'sections/menu' );
	?>
	<div id="main-content" class="main-content clearfix inner">
		<div class="section-title">
			<?php _e( 'Blog', 'noise' ); ?>
			<span class="desc"><?php _e( 'Single Post', 'noise' ) ?></span>
		</div>
		<div class="content-sidebar-wrap">
			<section id="content" class="content">
				<?php 
				if ( have_posts() ) : 
					while( have_posts() ) : the_post();
						get_template_part( 'content/content' );
					endwhile;
					noise_single_navigation();

					noise_get_author_box();

					if ( comments_open() )
						comments_template( '', true );
				endif;
				?>
			</section>
			<?php get_sidebar(); ?>
		</div>
	</div>
	<?php
	get_template_part( 'sections/connect' );
endif;

get_footer();
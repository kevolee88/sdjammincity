<?php
get_header();

if ( fitwp_option( 'blog_header' ) )
	get_template_part( 'sections/slider' );

get_template_part( 'sections/menu' );
?>

<div id="main-content" class="main-content clearfix inner">
	<h1 class="section-title">
		<?php printf( __( 'Search Results for: %s', 'noise' ), '<span>' . get_search_query() . '</span>'); ?>
	</h1>

	<div class="content-sidebar-wrap">
		<section id="content" class="content">
			<?php 
			if ( have_posts() ) :
				while( have_posts() ) : the_post();
					get_template_part( 'content/content' );
				endwhile;
				get_template_part( 'content/pagination' );
			else:
				printf( '<div class="alert notice"><p>%s</p></div>', __( 'No post found', 'noise' ) );
			endif;
			?>
		</section>

		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_template_part( 'sections/connect' ); ?>

<?php get_footer(); ?>
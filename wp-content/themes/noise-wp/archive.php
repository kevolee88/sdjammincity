<?php
get_header();

if ( fitwp_option( 'blog_header' ) )
	get_template_part( 'sections/slider' );

get_template_part( 'sections/menu' );
?>

<div id="main-content" class="main-content clearfix inner">
	<h1 class="section-title">
		<?php 
		if ( is_category() )
			single_cat_title();
		elseif ( is_tag() )
			single_tag_title();
		elseif ( is_day() || is_month() || is_year() )
			_e( 'Archive', 'noise' );
		elseif ( is_author() )
			_e( 'Author Archive', 'noise' );
		?>
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
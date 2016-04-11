<?php
get_header();

if ( fitwp_option( 'blog_header' ) )
	get_template_part( 'sections/slider' );

get_template_part( 'sections/menu' );
?>

<div id="main-content" class="main-content clearfix inner">
	<?php $page_id = get_option( 'page_for_posts' );  ?>
	<?php if( is_front_page() && $page_id ) : ?>
		<h1 class="section-title">
			<?php echo get_the_title( $page_id ); ?>
			<span class="desc"><?php echo get_post_meta( $page_id, 'title_desc', true ) ?></span>
		</h1>
	<?php endif; ?>

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
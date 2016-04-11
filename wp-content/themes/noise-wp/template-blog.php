<?php
/**
 * Template Name: Blog
 */

get_header();

if ( fitwp_option( 'blog_header' ) )
	get_template_part( 'sections/slider' );

get_template_part( 'sections/menu' );
?>

<div id="main-content" class="main-content clearfix inner">
	<h1 class="section-title">
		<?php the_title(); ?>
		<span class="desc"><?php echo get_post_meta( get_the_ID(), 'title_desc', true ) ?></span>
	</h1>

	<?php
	$noise_query = new WP_Query( array(
		'post_type' => 'post',
		'paged'     => max( 1, get_query_var( 'paged' ) )
	) );
	?>
	<div class="content-sidebar-wrap">
		<section id="content" class="content">
			<?php
			if ( $noise_query->have_posts() ) : 
				while( $noise_query->have_posts() ) : $noise_query->the_post();
					get_template_part( 'content/content' );
				endwhile;
				get_template_part( 'content/pagination' );
			else:
				printf( '<div class="alert notice"><p>%s</p></div>', __( 'No post found', 'noise' ) );
			endif;
			?>
		</section>
		<?php wp_reset_postdata(); ?>

		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_template_part( 'sections/connect' ); ?>

<?php get_footer(); ?>
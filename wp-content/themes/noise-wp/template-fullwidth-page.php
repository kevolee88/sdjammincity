<?php
/**
 * Template Name: Full Width Page
 */
get_header();
if ( fitwp_option( 'blog_header' ) )
	get_template_part( 'sections/slider' );

get_template_part( 'sections/menu' );
?>
<div id="main-content" class="main-content">
	<div class="inner">
		<?php
		if ( have_posts() ) :
			while( have_posts() ) : the_post();
			?>

			<article <?php post_class() ?>>
				<?php if ( ! noise_get_meta( 'hide_title' ) ) : ?>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>
				<?php endif;?>
				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages();
					edit_post_link( __( 'Edit This Page', 'noise' ), '<p class="edit-link">', '</p>' );
					?>
				</div>
			</article>

			<?php
			endwhile;

		endif;
		?>
	</div>
</div>
<?php
if ( ! noise_get_meta( 'hide_title' ) )
	get_template_part( 'sections/connect' );

get_footer();

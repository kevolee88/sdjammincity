<?php
get_header();
$is_woo = in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
if ( $is_woo && fitwp_option('shop_header') )
{
	get_template_part( "sections/slider" );
}

get_template_part( 'sections/menu' );
?>
<div id="main-content" class="main-content clearfix inner">
	<div class="content-sidebar-wrap">
		<section id="content" class="content">
			<?php
			if ( have_posts() ) :
				while( have_posts() ) : the_post();
				?>

				<article <?php post_class() ?>>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>
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

				if ( comments_open() )
					comments_template( '', true );
			endif;
			?>
		</section>
		<?php
		if ( ($is_woo && !is_cart() && !is_checkout() && !is_account_page() ) ||  !$is_woo)
		{
			get_sidebar();
		}
		?>
	</div>
</div>
<?php
get_template_part( 'sections/connect' );

get_footer();

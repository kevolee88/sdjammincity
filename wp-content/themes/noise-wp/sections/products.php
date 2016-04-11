<?php
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
{
	return;
}
$title  = noise_kses( fitwp_option( 'products_title' ) );
$suffix = noise_kses( fitwp_option( 'products_suffix' ) );
$desc   = noise_kses( fitwp_option( 'products_subtitle' ) );

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => true,
	'posts_per_page'      => intval( fitwp_option( 'products_num' ) ),
	'meta_query'          => array(
		array(
			'key'     => '_visibility',
			'value'   => array( 'catalog', 'visible' ),
			'compare' => 'IN',
		)
	),
);

$show = fitwp_option( 'products_show' );
switch ( $show ) {
	case 'featured':
		$args['meta_query'][] = array(
			'key' 		=> '_featured',
			'value' 	=> 'yes',
		);
		break;

	case 'best_selling':
		$args['meta_key'] = 'total_sales';
		$args['orderby']  = 'meta_value_num';
		break;

	case 'top_rate':
		$args['order']   = 'asc';
		$args['orderby'] = 'title';
		break;

	case 'sale':
		$product_ids_on_sale = wc_get_product_ids_on_sale();

		$meta_query   = array();
		$meta_query[] = WC()->query->visibility_meta_query();
		$meta_query[] = WC()->query->stock_status_meta_query();
		$meta_query   = array_filter( $meta_query );

		$args['order']         = 'asc';
		$args['orderby']       = 'title';
		$args['no_found_rows'] = 1;
		$args['meta_query']    = $meta_query;
		$args['post__in']      = array_merge( array( 0 ), $product_ids_on_sale );
		break;
}

if ( $show == 'top_rate' )
	add_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );

$products = new WP_Query( $args );

if ( $show == 'top_rate' )
	remove_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );
?>

<section id="section-products" class="section-products section woocommerce">
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

		<?php if ( $products->have_posts() ) : ?>
		<div id="products-slider" class="products-slider media-slider vertical-slider tiny-carousel">
			<div id="products-nav" class="flex-direction-nav">
				<a class="flex-prev prev"></a>
				<a class="flex-next next"></a>
			</div>
			<div class="viewport">
				<ul class="slides overview">
					<?php
					while( $products->have_posts() ) : $products->the_post();
					?>

						<?php echo ( $products->current_post % 5 == 0 ) ? '<li><ul class="products">' : ''; ?>
							<?php wc_get_template_part( 'content', 'product' ) ?>
						<?php echo ( $products->current_post % 5 == 4 || $products->post_count == $products->current_post + 1 ) ? '</ul></li>' : ''; ?>

					<?php
					endwhile;
					wp_reset_postdata();
					?>
				</ul>

			</div>
		</div><!-- end .latest-tracks-slider -->
		<?php endif; ?>
	</div>
</section>

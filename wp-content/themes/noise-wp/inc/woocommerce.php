<?php
/**
 * Format sale flash structure
 *
 * @since  2.4
 *
 * @param  string $onsale
 *
 * @return string
 */
function noise_sale_flash( $onsale )
{
	return '<span class="onsale-custom">' . __( 'Sale', 'noise' ) . '</span>';
}

/**
 * Create product footer
 *
 * @since  2.4
 * @return void
 */
function noise_product_footer()
{
	woocommerce_template_loop_add_to_cart();
	?>
	<div class="product-price"><?php woocommerce_template_loop_price()?></div>
	<?php
}

/**
 * Display product thumnail
 *
 * @since  2.4
 * @return void
 */
function noise_product_thumnail()
{
	global $product;
	if ( $product->is_on_sale() )  {
	?>
		<div class="sale-price">
		<?php
			woocommerce_show_product_loop_sale_flash();
			woocommerce_template_loop_price();
		?>
		</div>
	<?php
	}
	noise_get_image( 'size=shop_catalog' );
}

/**
 * Format single product header
 *
 * @since  2.4
 *
 * @return void
 */
function noise_single_product_header()
{
	?>
	<h1 class="section-title">
	<?php woocommerce_page_title();?>
	<span class="desc">Single product</span></h1>
	<?php
}

/**
 * Format single product before summary
 *
 * @since  2.4
 *
 * @return void
 */
function noise_single_product_before_summary()
{
	?>
	<div class="product-title-sale">
	<?php
		woocommerce_template_single_title();
		woocommerce_show_product_sale_flash();
	?>
	</div>
	<div class="product-price-cat">
	<?php
		woocommerce_template_single_price();
		woocommerce_template_single_meta();
	?>
	</div>
	<?php
}

/**
 * Format cart product header
 *
 * @since  2.4
 *
 * @return void
 */
function noise_cart_header()
{
	?>
	<h1 class="section-title">
	<?php the_title();?>
	<span class="desc">Selected products</span></h1>
	<?php
}

/**
 * Ajaxify your cart viewer
 *
 * @since 2.4
 *
 * @param array $fragments
 *
 * @return array
 */
function noise_add_to_cart_fragments( $fragments )
{
	global $woocommerce;

	if ( empty( $woocommerce ) )
		return $fragments;

	ob_start();
	?>

	<a href="<?php echo $woocommerce->cart->get_cart_url() ?>" class="cart-contents" title="<?php _e( 'View your shopping cart', 'noise' ) ?>">
		<span class="icon-shopping-cart"></span><span class="mini-cart-counter"><?php echo $woocommerce->cart->cart_contents_count; ?> </span>
	</a>

	<?php

	$fragments['a.cart-contents'] = ob_get_clean();

	return $fragments;
}

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_filter( 'woocommerce_sale_flash', 'noise_sale_flash' );
add_action( 'woocommerce_before_shop_loop_item_title', 'noise_product_thumnail', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_after_shop_loop_item','noise_product_footer', 10 );
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_before_single_product', 'noise_single_product_header', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'noise_single_product_before_summary', 5 );

// hooks cart page
add_action( 'woocommerce_before_cart', 'noise_cart_header', 10 );
remove_action( 'woocommerce_before_cart_table', 'the_title', 10 );
add_filter( 'add_to_cart_fragments', 'noise_add_to_cart_fragments' );

// For version 2.3.x or above
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

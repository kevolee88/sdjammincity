<?php
add_filter( 'wp_title', 'noise_wp_title', 10, 3 );

/**
 * Get meta <title>
 *
 * @param  string $title
 * @param  string $sep
 * @param  string $seplocation
 *
 * @return string
 * @see    http://www.deluxeblogtips.com/2012/03/better-title-meta-tag.html
 * @since  1.0
 */
function noise_wp_title( $title, $sep, $seplocation )
{
	global $page, $paged;
	if ( is_feed() )
		return $title;

	if ( 'right' == $seplocation )
		$title .= get_bloginfo( 'name' );
	else
		$title = get_bloginfo( 'name' ) . $title;

	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " {$sep} {$site_description}";

	if ( $paged >= 2 || $page >= 2 )
		$title .= " {$sep} " . sprintf( __( 'Page %s', 'noise' ), max( $paged, $page ) );

	return $title;
}

add_action( 'wp_head', 'noise_custom_header' );

/**
 * Apply theme's style settings, header settings
 *
 * @since  1.0
 * @return void
 */
function noise_custom_header()
{
	if ( $favicon = fitwp_option( 'favicon' ) )
		echo '<link rel="icon" href="' . esc_url( $favicon ) . '">';

	if ( $touch_icon = fitwp_option( 'touch_icon' ) )
		echo '<link rel="apple-touch-icon" href="' . esc_url( $touch_icon ) . '">';

	if ( $header_scripts = fitwp_option( 'header_scripts' ) )
		echo $header_scripts;
}

add_action( 'wp_footer', 'noise_footer_scripts', 99 );

/**
 * Add custom scripts to footer
 *
 * @since  1.0
 * @return void
 */
function noise_footer_scripts()
{
	if ( $footer_scripts = fitwp_option( 'footer_scripts' ) )
		echo $footer_scripts;
}

add_filter( 'body_class', 'noise_body_class' );

/**
 * Filter function to add classes to body
 *
 * @since  1.0
 * @param  array $classes
 * @return array
 */
function noise_body_class( $classes )
{
	// Color scheme
	$custom_color_scheme = fitwp_option( 'custom_color_scheme' );

	if ( ! $custom_color_scheme )
		$classes[] = fitwp_option( 'color_scheme' );
	else
		$classes[] = 'custom-color-scheme';

	// Add class to for opener section
	if ( fitwp_option( 'opener_enable' ) && !isset( $_COOKIE['unlock'] ) )
		$classes[] = 'opener';

	// Layout class for blog
	$classes[] = fitwp_option( 'blog_layout' );


	$detect = new Mobile_Detect;
	if ( $detect->isMobile() )
		$classes[] = 'mobile';

	return $classes;
}

add_filter( 'post_class', 'noise_post_class' );

/**
 * Filter function to add class to post
 *
 * @since  1.0
 * @param  array $classes
 * @return array
 */
function noise_post_class( $classes )
{
	if ( 'audio' != get_post_format() )
		return $classes;

	$audio = get_post_meta( get_the_ID(), 'audio', true );
	$ext = pathinfo( $audio, PATHINFO_EXTENSION );

	if ( empty( $ext ) )
	{
		$classes[] = 'format-audio-external';
	}
	elseif ( get_post_meta( get_the_ID(), 'spectrum', true ) )
	{
		$classes[] = 'format-audio-spectrum';
	}

	return $classes;
}

add_action( 'template_redirect', 'noise_template_redirect' );

/**
 * Redirect to homepage when under construction mode enable
 *
 * @since  1.0
 * @return void
 */
function noise_template_redirect()
{
	$uc_mode = fitwp_option( 'uc_mode' );

	if ( $uc_mode && !is_page_template( 'template-under-construction.php' ) )
	{
		$pages = get_pages( array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'template-under-construction.php'
		) );

		if ( !empty( $pages ) )
		{
			$page = array_shift( $pages );
			$url = get_page_link( $page->ID );

			wp_redirect( $url );
			exit;
		}
	}
}

/**
 * Add icon cart to menu
 *
 * @since  1.0
 * @return void
 */
add_filter( 'wp_nav_menu_items', 'noise_add_cart_icon_to_menu', 10, 2 );
function noise_add_cart_icon_to_menu ( $items, $args )
{
	if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
		return $items;

	global $woocommerce;
	if (fitwp_option( 'cart_on_menu' ) && $args->theme_location == 'primary')
	{
		$items .= '<li>
			<div class="mini-cart">
				<a href="'.$woocommerce->cart->get_cart_url() .'" class="cart-contents" title="' . __( 'View your shopping cart', 'noise' ) . '">
					<span class="icon-shopping-cart"></span><span class="mini-cart-counter">'. $woocommerce->cart->cart_contents_count . ' </span>
				</a>
			</div>
		</li>';
	}

	return $items;
}

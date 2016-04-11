<?php
add_shortcode( 'year', 'noise_shortcode_year' );

/**
 * Shortcode [year]
 *
 * @since 1.0.1
 * @param array  $atts    Shortcode attributes
 * @param string $content Shortcode content
 *
 * @return string
 */
function noise_shortcode_year( $atts, $content = null )
{
	return date( 'Y' );
}

add_shortcode( 'bloginfo', 'noise_shortcode_bloginfo' );
/**
 * Shortcode [bloginfo]
 * A wrapper short code of get_bloginfo() function
 *
 * @since 1.0.1
 *
 * @param array  $atts    Shortcode attributes
 * @param string $content Shortcode content
 *
 * @return string
 */
function noise_shortcode_bloginfo( $atts, $content = null )
{
	extract( shortcode_atts( array(
		'name' => 'name',
	), $atts ) );

	return get_bloginfo( $name );
}

add_shortcode( 'site_link', 'noise_shortcode_site_link' );
/**
 * Shortcode to display a link back to the site.
 *
 * @since 1.0.1
 *
 * @param array $atts Shortcode attributes
 *
 * @return string
 */
function noise_shortcode_site_link( $atts )
{
	$name = get_bloginfo( 'name' );
	return '<a class="site-link" href="' . HOME_URL . '" title="' . esc_attr( $name ) . '" rel="home">' . $name . '</a>';
}

add_shortcode( 'copyright', 'noise_shortcode_copyright' );
/**
 * Shortcode to display a link back to the site.
 *
 * @since 1.1
 *
 * @param array $atts Shortcode attributes
 *
 * @return string
 */
function noise_shortcode_copyright( $atts, $content = null )
{
	extract( shortcode_atts( array(
		'title' => '',
	), $atts ) );

	return sprintf(
		'<span class="copyright-shortcode">%s<span class="copyright-info">%s</span></span>',
		$title,
		do_shortcode( $content )
	);
}
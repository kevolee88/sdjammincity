<?php
/**
 * Filter for output HTML, get value, save value of a field
 * @param string $name  Based filter name
 * @param mixed  $value Value
 * @return mixed
 */
function fitwp_options_filter( $name, $value )
{
	// Get function arguments
	// 0 - $name
	// 1 - $value
	// 2 - field (optional)
	// 3, ... - other params (optional)
	$args = func_get_args();
	$args = array_slice( $args, 2 );
	$tags = array( "fitwp_options_$name" );
	if ( !empty( $args ) )
	{
		$field = array_shift( $args );
		if ( isset( $field['type'] ) )
			$tags[] = "fitwp_options_{$name}_{$field['type']}";
		if ( isset( $field['id'] ) )
			$tags[] = "fitwp_options_{$name}_{$field['id']}";
	}
	array_unshift( $args, $value );
	foreach ( $tags as $tag )
	{
		$value = apply_filters_ref_array( $tag, $args );
	}

	return $value;
}

/**
 * Simple HTML helper class
 */
class FitWP_Options_HTML
{
	/**
	 * Magic method to output a HTML tag
	 *
	 * @param string $tag       Tag name
	 *
	 * @param mixed  $args Function arguments
	 *
	 * @return string
	 * @since 1.0
	 */
	function __call( $tag, $args )
	{
		array_unshift( $args, $tag );
		return call_user_func_array( array( $this, 'tag' ), $args );
	}

	/**
	 * Get HTML for a tag
	 *
	 * @param string $tag  Tag name
	 * @param mixed  $atts Tag attributes
	 * @return string
	 * @since 1.0
	 */
	function tag( $tag, $atts = '' )
	{
		// Convert simple string 'class=name' to array( 'class' => 'name' )
		$atts = array_map( 'esc_attr', wp_parse_args( $atts ) );
		$s = '';
		foreach ( $atts as $k => $v )
		{
			$s .= " $k='$v'";
		}
		$args = func_get_args();
		return in_array( $tag, array( 'input', 'hr', 'img', 'br' ) )
			? sprintf( '<%s%s>', $tag, $s )
			: sprintf( '<%1$s%2$s>%3$s</%1$s>', $tag, $s, implode( '', array_slice( $args, 2 ) ) );
	}
}

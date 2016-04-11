<?php
// Deprecated! move to noise_kses function
$_noise_allowed_tags = array(
	'span'   => array(
		'class'  => array(),
		'style'  => array(),
	),
	'a'      => array(
		'class'  => array(),
		'style'  => array(),
		'href'   => array(),
		'title'  => array(),
	),
	'em'     => array(),
	'strong' => array(),
	'b' => array(),
	'i' => array(),
	'br' => array(),
);


/**
 * Skip tags are not allowed from given string
 *
 * @since  1.1
 * @param  string $str
 * @param  array  $tags
 * @return string
 */
function noise_kses( $str, $tags = array() )
{
	$default = array(
		'span'   => array(
			'class'  => array(),
			'style'  => array(),
		),
		'a'      => array(
			'class'  => array(),
			'style'  => array(),
			'href'   => array(),
			'title'  => array(),
		),
		'em'     => array(),
		'strong' => array(),
		'b' => array(),
		'i' => array(),
		'br' => array(),
	);

	$tags = wp_parse_args( (array) $tags, $default );

	return wp_kses( $str, $tags );
}

/**
 * Get post meta
 *
 * @since  1.0
 * @param  string $key
 * @param  array  $args
 * @param  int    $post_id
 * @return mixed
 */
function noise_get_meta( $key, $args = array(), $post_id = null )
{
	if ( !function_exists( 'rwmb_meta' ) )
		return false;

	return rwmb_meta( $key, $args, $post_id );
}


/**
 * Display or get post image
 *
 * @since 1.0
 * @param array $args
 *
 * @return void|string
 */
function noise_get_image( $args = array() )
{
	$default = apply_filters(
		'noise_get_image_default_args',
		array(
			'post_id'   => 0,
			'size'      => 'thumbnail',
			'format'    => 'html', // html or src
			'attr'      => '',
			'thumbnail' => true,
			'meta_key'  => '',
			'scan'      => true,
			'default'   => '',
			'echo'      => true,
		)
	);

	$args = wp_parse_args( $args, $default );

	if ( !$args['post_id'] )
		$args['post_id'] = get_the_ID();

	// Get image from cache
	$key = md5( serialize( $args ) );
	$image_cache = wp_cache_get( $args['post_id'], 'noise_get_image' );

	if ( !is_array( $image_cache ) )
		$image_cache = array();

	if ( empty( $image_cache[$key] ) )
	{
		// Get post thumbnail
		if ( has_post_thumbnail( $args['post_id'] ) && $args['thumbnail'] )
		{
			$id = get_post_thumbnail_id( $args['post_id'] );
			$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
			list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
		}

		// Get the first image in the custom field
		if ( !isset( $html, $src ) && $args['meta_key'] )
		{
			$id = get_post_meta( $args['post_id'], $args['meta_key'], true );

			// Check if this post has attached images
			if ( $id )
			{
				$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
				list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
			}
		}

		// Get the first attached image
		if ( !isset( $html, $src ) )
		{
			$image_ids = array_keys( get_children( array(
				'post_parent'    => $args['post_id'],
				'post_type'	     => 'attachment',
				'post_mime_type' => 'image',
				'orderby'        => 'menu_order',
				'order'	         => 'ASC',
			) ) );

			// Check if this post has attached images
			if ( !empty( $image_ids ) )
			{
				$id = $image_ids[0];
				$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
				list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
			}
		}

		// Get the first image in the post content
		if ( !isset( $html, $src ) && ( $args['scan'] ) )
		{
			preg_match( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $args['post_id'] ), $matches );

			if ( !empty( $matches ) )
			{
				$html = $matches[0];
				$src = $matches[1];
			}
		}

		// Use default when nothing found
		if ( !isset( $html, $src ) && !empty( $args['default'] ) )
		{
			if ( is_array( $args['default'] ) )
			{
				$html = @$args['html'];
				$src = @$args['src'];
			}
			else
			{
				$html = $src = $args['default'];
			}
		}

		// Still no images found?
		if ( !isset( $html, $src ) )
			return false;

		$output = 'html' === strtolower( $args['format'] ) ? $html : $src;

		$image_cache[$key] = $output;
		wp_cache_set( $args['post_id'], $image_cache, 'noise_get_image' );
	}
	// If image already cached
	else
	{
		$output = $image_cache[$key];
	}

	$output = apply_filters( 'noise_get_image', $output, $args );

	if ( !$args['echo'] )
		return $output;

	echo $output;
}
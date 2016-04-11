<?php
$default = array(
	'slider',
	'menu',
	'player',
	'latest-tracks',
	'like',
	'popular-tracks',
	'testimonials',
	'media',
	'latest-tweets',
	'releases',
	'subscribe',
	'events',
	'countdown',
	'artists',
	'connect',
);
$static_sections =  array( 'slider', 'menu', 'player' );

$sections = fitwp_option( 'sections' );
$sections = empty( $sections ) ? $default : array_merge( $static_sections, $sections );

foreach ( $sections as $key => $section )
{
	if ( 'menu' != $section && 'player' != $section )
	{
		$enable = str_replace( '-', '_', $section ) . '_enable';
		if ( !fitwp_option( $enable ) )
			continue;
	}

	get_template_part( "sections/$section" );
}
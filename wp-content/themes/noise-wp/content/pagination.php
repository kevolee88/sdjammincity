<?php
global $wp_query, $noise_query;

$query = is_page_template( 'template-blog.php' ) ? $noise_query : $wp_query;

// Don't print empty markup in archives if there's only one page.
if ( $query->max_num_pages >= 2 )
{
	switch ( fitwp_option( 'pagination' ) )
	{
		case 'ajax':
			noise_ajax_pagination( $query );
			break;

		case 'numberic':
			noise_numeric_pagination( $query );
			break;
		
		default:
			noise_simple_pagination( $query );
			break;
	}
}
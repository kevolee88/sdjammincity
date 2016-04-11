<?php
define( 'HOME_URL', trailingslashit( home_url() ) );
define( 'THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'THEME_URL', trailingslashit( get_template_directory_uri() ) );

require THEME_DIR . 'inc/post-types.php';
require THEME_DIR . 'inc/helpers.php';

// Theme options
require THEME_DIR . 'inc/functions/options/options.php';
require THEME_DIR . 'inc/options.php';

// Custom css
require THEME_DIR . 'inc/custom-css.php';

// Helper functions for track
require THEME_DIR . 'inc/track.php';

require THEME_DIR . 'inc/woocommerce.php';


// Widget
require THEME_DIR . 'inc/widgets/artists.php';
require THEME_DIR . 'inc/widgets/tweets.php';
require THEME_DIR . 'inc/widgets/recent-posts.php';
require THEME_DIR . 'inc/widgets/tabs.php';
require THEME_DIR . 'inc/widgets/featured-tracks.php';

if ( is_admin() )
{
	require THEME_DIR . 'inc/admin/admin.php';

	require THEME_DIR . 'inc/admin/meta-box.php';
	require THEME_DIR . 'inc/functions/class-tgm-plugin-activation.php';

	if ( defined( 'DOING_AJAX' ) )
		require THEME_DIR . 'inc/admin/ajax.php';
}
else
{
	require THEME_DIR . 'inc/functions/Mobile_Detect.php';
	require THEME_DIR . 'inc/frontend/media.php';
	require THEME_DIR . 'inc/frontend/frontend.php';
	require THEME_DIR . 'inc/frontend/entry.php';
	require THEME_DIR . 'inc/frontend/comments.php';
	require THEME_DIR . 'inc/functions/shortcodes.php';
}

add_action( 'after_setup_theme', 'noise_setup' );

/**
 * Setup theme supports
 *
 * @return void
 * @since 1.0
 */
function noise_setup()
{
	global $content_width;

	// Theme supports
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'menus' );
	add_theme_support( 'html5' );
	add_theme_support( 'post-formats', array( 'image', 'gallery', 'video', 'audio', 'link', 'quote' ) );
	add_theme_support( 'woocommerce' );

	// Menus
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'noise' ),
	) );

	$sidebars = array(
		'blog-sidebar' => __( 'Blog Sidebar', 'noise' ),
		'footer-1'     => __( 'Footer Sidebar 1', 'noise' ),
		'footer-2'     => __( 'Footer Sidebar 2', 'noise' ),
		'footer-3'     => __( 'Footer Sidebar 3', 'noise' ),
		'footer-4'     => __( 'Footer Sidebar 4', 'noise' ),
	);
	// Sidebars
	foreach ( $sidebars as $id => $name )
	{
		register_sidebar( array(
			'name'          => $name,
			'id'            => $id,
			'before_widget' => '<div class="widget %2$s" id="%1$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );
	}

	// Image sizes
	set_post_thumbnail_size( 190, 190, true );
	add_image_size( 'track-thumbnail', 70, 70, true );
	add_image_size( 'track-preview', 530, 250, true );
	add_image_size( 'album-thumbnail', 1150, 430, true );
	add_image_size( 'album-preview', 525, 200, true );
	add_image_size( 'player-expanded', 240, 280, true );
	add_image_size( 'artist-thumbnail', 350, 450, true );
	add_image_size( 'blog-small-thumbnaill', 350, 200, true );
	add_image_size( 'blog-thumbnail', 690, 330, true );
	add_image_size( 'widget-thumb', 60, 60, true );

	// Set the content width based on the theme's design and stylesheet
	if ( !isset( $content_width ) )
		$content_width = 770;

	load_theme_textdomain( 'noise', get_template_directory() . '/lang' );
}

<?php
add_action( 'init', 'noise_register_media' );

/**
 * Register media libraries for faster used
 *
 * @return void
 * @since 1.0
 */
function noise_register_media()
{
	$suffix = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';

	wp_register_script( 'jquery-colorbox', THEME_URL . 'js/jquery.colorbox-min.js', array( 'jquery' ), '1.4.33', true );
	wp_register_script( 'jquery-flexslider', THEME_URL . 'js/jquery.flexslider-min.js', array( 'jquery' ), '2.2.2', true );
	wp_register_script( 'jquery-tinycarousel', THEME_URL . 'js/jquery.tinycarousel.min.js', array( 'jquery' ), '2.1.4', true );
	wp_register_script( 'jquery-udraggable', THEME_URL . 'js/jquery.udraggable.min.js', array( 'jquery' ), '0.3', true );
	wp_register_script( 'jquery-soical-likes', THEME_URL . 'js/social-likes.min.js', array( 'jquery' ), '3.0.4', true );
	wp_register_script( 'jquery-mcustomscrollbar', THEME_URL . 'js/jquery.mCustomScrollbar.min.js', array( 'jquery' ), '2.8.3', true );
	wp_register_script( 'jquery-fitvids', THEME_URL . 'js/jquery.fitvids.min.js', array( 'jquery' ), '1.0.3', true );
	wp_register_script( 'jquery-queryloader2', THEME_URL . 'js/jquery.queryloader2.min.js', array( 'jquery' ), '2.2', true );
	wp_register_script( 'jquery-smoothscroll', THEME_URL . 'js/jquery.smoothscroll.min.js', array( 'jquery' ), '', true );

	wp_register_script( 'google-maps-api', '//maps.google.com/maps/api/js?sensor=false', array(), '3', true );

	wp_register_script( 'noise-mediaelement', THEME_URL . "js/mediaelement-and-player.min.js", array( 'jquery' ), '2.13.0', true );
	wp_register_script( 'noise-playlist', THEME_URL . "js/playlist$suffix.js", array( 'noise-mediaelement' ), '', true );
	wp_register_script( 'noise-visualization', THEME_URL . "js/visualization$suffix.js", array( 'jquery' ), '', true );

	wp_register_script( 'noise-scripts', THEME_URL . "js/scripts$suffix.js", array(
		'jquery-queryloader2',
		'google-maps-api',
		'jquery-fitvids',
		'jquery-mcustomscrollbar',
		'jquery-flexslider',
	), '1.0', true );

	wp_register_script( 'noise', THEME_URL . "js/noise$suffix.js", array(
		'jquery-flexslider',
		'jquery-tinycarousel',
		'jquery-mcustomscrollbar',
		'jquery-soical-likes',
		'jquery-colorbox',
	), '1.0', true );

	wp_register_script( 'noise-player', THEME_URL . "js/player$suffix.js", array( 'wp-mediaelement', 'noise-playlist', 'noise-visualization' ), '1.0', true );
	wp_register_script( 'noise-entry', THEME_URL . "js/entry$suffix.js", array( 'jquery', 'noise-mediaelement', 'wp-mediaelement', 'noise-visualization', 'jquery-flexslider' ), '1.0', true );
}

add_action( 'wp_enqueue_scripts', 'noise_enqueue_scripts' );

/**
 * Enqueue scripts and styles for frontend
 *
 * @return void
 * @since 1.0
 */
function noise_enqueue_scripts()
{
	$detect = new Mobile_Detect;

	// Load main stylesheet
	wp_enqueue_style( 'google-fonts', 'http://fonts.googleapis.com/css?family=Lato|Oswald:400,300,700' );
	wp_enqueue_style( 'noise', get_stylesheet_uri() );

	wp_deregister_style( 'mediaelement' );
	wp_deregister_style( 'wp-mediaelement' );

	if ( fitwp_option( 'opener_enable' ) && !isset( $_COOKIE['unlock'] ) )
		wp_enqueue_script( 'jquery-udraggable' );

	wp_enqueue_script( 'noise-scripts' );
	wp_localize_script(
		'noise-scripts',
		'noiseVars',
		array(
			'ajax_url'    => admin_url( 'admin-ajax.php' ),
			'autoplay'    => fitwp_option( 'autoplay' ),
			'preloader'  => fitwp_option( 'preloader' ),
			'navDefault'  => __( 'Menu', 'noise' ),
			'marker'      => THEME_URL . 'img/marker.png',
			'contact'     => wpautop( fitwp_option( 'contact_info' ) ),
			'isMobile'    => $detect->isMobile(),
			'bgColor'     => fitwp_option( 'custom_color_scheme' ) ? fitwp_option( 'custom_color_2' ) : '#383838',
			'quotesAuto'  => fitwp_option( 'testimonials_autoslide' ),
			'tweetsAuto'  => fitwp_option( 'tweets_autoslide' ),
			'autoplaysoundvideo'    => fitwp_option( 'auto_play_sound_video' ),
		)
	);
	if ( is_page_template( 'template-onepage.php' ) )
	{
		wp_enqueue_script( 'noise' );
		wp_enqueue_script( 'noise-player' );
	}
	else
	{
		wp_enqueue_script( 'noise-entry' );
	}

	if ( is_singular() && get_option( 'thread_comments' ) && comments_open() )
		wp_enqueue_script( 'comment-reply' );

	if ( !$detect->isMobile() && !$detect->isTablet() )
		wp_enqueue_script( 'jquery-smoothscroll' );
}

add_action( 'wp_head', 'noise_custom_css' );

/**
 * Apply custom css from page meta
 *
 * @since  1.0
 * @return void
 */
function noise_custom_css()
{
	$style = '';

	// Under construction template
	if ( is_page_template( 'template-under-construction.php' ) )
	{
		$uc_bg = noise_get_meta( 'uc_bg', 'type=image_advanced' );
		$uc_bg = array_shift( $uc_bg );
		$style .= empty( $uc_bg['full_url'] ) ? '' : sprintf( 'body.page-template-template-under-construction-php .parallax { background-image: url(%s) }', $uc_bg['full_url'] );
	}

	if ( !empty( $style ) )
		printf( '<style>%s</style>', $style );
}

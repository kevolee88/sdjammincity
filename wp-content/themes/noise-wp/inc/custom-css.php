<?php

/**
 * This class generates custom CSS into static CSS file in uploads folder
 * and enqueue it in the frontend
 *
 * CSS is generated only when theme options is saved (changed)
 * Works with LESS (for unlimited color schemes)
 */
class Noise_Custom_CSS
{
	/**
	 * @var string Custom CSS file name
	 */
	public $file = 'custom.css';

	/**
	 * Add hooks
	 *
	 * @return Noise_Custom_CSS
	 */
	function __construct()
	{
		add_action( 'fitwp_options_save', array( $this, 'generate_css' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_css' ), 20 );
	}

	/**
	 * Generate custom CSS when save theme options
	 *
	 * @return void
	 */
	function generate_css()
	{
		// Update last time saved
		set_theme_mod( 'last_saved', time() );

		$css = '';
		$css .= $this->design_custom_css();
		$css .= $this->custom_color_scheme();

		// Write custom CSS to static file
		$dir = $this->get_dir();
		$file = $dir . $this->file;
		if ( $css )
		{
			wp_mkdir_p( $dir ); // Create directory if it doesn't exists
			@file_put_contents( $file, $css );
		}
		else
		{
			// Remove file and directory
			@unlink( $file );
			@rmdir( $dir );
		}
	}

	/**
	 * Enqueue custom CSS
	 *
	 * @return void
	 */
	function enqueue_css()
	{
		if ( !file_exists( $this->get_dir() . $this->file ) )
			return;

		wp_enqueue_style( get_template() . '-custom-css', $this->get_dir( false ) . $this->file, '', fitwp_option( 'last_saved' ) );
	}

	/**
	 * Get (and create if unavailable) the compiled CSS directory
	 *
	 * @param  bool $get_path true = returns the system path, false = URL
	 *
	 * @return string $dir The system path or URL of the folder
	 */
	function get_dir( $get_path = true )
	{
		$upload_dir = wp_upload_dir();
		$base = $get_path ? $upload_dir['basedir'] : $upload_dir['baseurl'];
		$dir_name = 'fitwp-css';
		$dir = path_join( $base, $dir_name );
		return trailingslashit( $dir );
	}

	/**
	 * Get custom CSS in design section
	 *
	 * @return string
	 */
	function design_custom_css()
	{
		$css = '';

		// Custom background
		$bg = fitwp_option( 'background' );
		if ( !is_array( $bg ) )
			$bg = array();
		if ( !empty( $bg['color'] ) || !empty( $bg['image'] ) )
		{
			$bg_css = 'body {';
			if ( !empty( $bg['color'] ) )
				$bg_css .= 'background-color: ' . $bg['color'] . ';';
			if ( !empty( $bg['image'] ) )
				$bg_css .= 'background-image: url(' . $bg['image'] . ');';
			if ( !empty( $bg['repeat'] ) )
				$bg_css .= 'background-repeat: ' . $bg['repeat'] . ';';
			if ( !empty( $bg['attachment'] ) )
				$bg_css .= 'background-attachment: ' . $bg['attachment'] . ';';
			if ( !empty( $bg['position_x'] ) )
				$bg_css .= 'background-position-x: ' . $bg['position_x'] . ';';
			if ( !empty( $bg['position_y'] ) )
				$bg_css .= 'background-position-y: ' . $bg['position_y'] . ';';
			$bg_css .= '}';

			$css .= $bg_css;
		}

		// 404 page
		$bg_404 = fitwp_option( '404_background' );
		$css .= empty( $bg_404 ) ? '' : sprintf( 'body.error404  .not-found .parallax { background-image: url(%s) }', esc_url( $bg_404 ) );

		// Logo
		$nav_logo    = fitwp_option( 'logo' );
		$logo_width  = fitwp_option( 'logo_width' );
		$logo_height = fitwp_option( 'logo_height' );
		if ( !empty( $nav_logo ) )
		{
			$custom_logo = sprintf( 'background-image: url(%s);', esc_url( $nav_logo ) );

			if ( !empty( $logo_width ) && !empty( $logo_height ) )
				$custom_logo .= sprintf( 'width: %spx; height: %spx; background-size: contain', intval( $logo_width ), intval( $logo_height ) );

			$css .= '.nav-menu .logo { ' . $custom_logo . ' }';
		}

		// Opener logo
		$opener_logo = fitwp_option( 'opener_logo' );
		if ( !empty( $opener_logo ) )
			$css .= sprintf( '.section-opener .logo { background-image: url(%s) }', esc_url( $opener_logo ) );

		// Sections
		$sections = array(
			'opener',
			'like',
			'testimonials',
			'latest-tweets',
			'subscribe',
			'countdown',
			'connect',
			'blog',
			'shop',
		);

		// Parallax background
		foreach( $sections as $section )
		{
			$background = fitwp_option( str_replace('-', '_', $section ) . '_bg' );
			$selector = 'opener' == $section ? "#section-$section" : "#section-$section .parallax";
			$css .= empty( $background ) ? '' : sprintf( '%s { background-image: url(%s); }', $selector, esc_url( $background ) );
		}

		// Parallax pattern
		$pattern = fitwp_option( 'parallax_pattern' );
		$css .= empty( $pattern ) ? '' : sprintf( '.section-mark { background-image: url(%s); }', esc_url( $pattern ) );

		// Top slider
		$parallax = fitwp_option( 'slider_parallax_image' );
		$css .= empty( $parallax ) ? '' : sprintf( '#section-slider .parallax { background-image: url(%s); }', esc_url( $parallax ) );

		// Custom CSS
		if ( $custom_css = fitwp_option( 'custom_css' ) )
			$css .= $custom_css;

		// Featured title area background
		if ( $bg = fitwp_option( 'featured_title_background' ) )
			$css .= ".featured-title { background: url($bg); }";

		// Hide button sound in Background Video
		if( !fitwp_option( 'button_sound_bg_video' ) )
			$css .= "#toggle-bg-sound { display: none; }";

		return $css;
	}

	/**
	 * Get CSS for custom color scheme
	 *
	 * @return string
	 */
	function custom_color_scheme()
	{
		$enable = fitwp_option( 'custom_color_scheme' );
		if ( !$enable )
			return '';

		$color_1 = fitwp_option( 'custom_color_1' );
		$color_2 = fitwp_option( 'custom_color_2' );
		if ( !$color_1 || !$color_2 )
			return '';

		// Prepare LESS to compile
		$less = file_get_contents( THEME_DIR . 'less/mixins.less' );
		$less .= "
			.color-scheme($color_1, $color_2);
		";

		// Compile
		require THEME_DIR . 'inc/functions/lessc.inc.php';
		$compiler = new lessc;
		$compiler->setFormatter( 'compressed' );
		return $compiler->compile( $less );
	}
}

new Noise_Custom_CSS;

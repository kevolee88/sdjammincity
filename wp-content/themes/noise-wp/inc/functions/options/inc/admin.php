<?php
/**
 * Framework Admin
 * Display admin page
 *
 * @package   FitWP Options Framework
 * @author    The FitWP Team <anh@fitwp.com>
 * @copyright Copyright (c) 2013 by fitwp.com
 * @version   1.0
 */
class FitWP_Options_Admin
{
	/**
	 * Setup the admin page
	 *
	 * @return FitWP_Options_Admin
	 */
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		$actions = array( 'save', 'reset', 'import' );
		foreach ( $actions as $action )
		{
			add_action( "wp_ajax_fitwp_options_$action", array( $this, $action ) );
		}
	}

	/**
	 * Register admin menu
	 *
	 * @return  void
	 */
	function admin_menu()
	{
		$hook = add_theme_page( __( 'Theme Options', 'fitwp' ), __( 'Theme Options', 'fitwp' ), 'edit_theme_options', 'theme-options', array( $this, 'show' ) );

		add_action( "admin_print_styles-$hook", array( $this, 'enqueue' ) );
	}

	/**
	 * Enqueue scripts and styles for options page
	 *
	 * @return void
	 */
	function enqueue()
	{
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_style( 'fitwp-options', FITWP_OPTIONS_URL . 'css/admin.css' );
		wp_enqueue_script( 'fitwp-options', FITWP_OPTIONS_URL . 'js/admin.js', array( 'jquery' ), '', true );
		wp_localize_script( 'fitwp-options', 'FitWP_Options', array(
			'nonce_save'    => wp_create_nonce( 'save' ),
			'nonce_reset'   => wp_create_nonce( 'reset' ),
			'nonce_import'  => wp_create_nonce( 'import' ),
			'reset_notice'  => __( 'This action can\'t be undo. Are you sure want to reset?', 'fitwp' ),
			'import_notice' => __( 'All previous options will be overwritten.  Are you sure want to import?', 'fitwp' ),
			'media_title'   => __( 'Select Image', 'fitwp' ),
		) );

		// Allow themes to enqueue scripts, styles
		do_action( 'fitwp_options_enqueue' );
	}

	/**
	 * Render admin page
	 *
	 * @return  void
	 */
	function show()
	{
		include FITWP_OPTIONS_DIR . 'inc/page.php';
	}

	/**
	 * Save theme options
	 *
	 * @return void
	 */
	function save()
	{
		check_ajax_referer( 'save' );
		$_POST['data'] = stripcslashes( $_POST['data'] );
		parse_str( $_POST['data'], $data );
		$data = stripslashes_deep( $data );
		foreach ( FitWP_Options::$fields as $id => $field )
		{
			$value = isset( $data[$id] ) ? $data[$id] : '';

			// Allow to change value before saving to database
			$value = fitwp_options_filter( 'set_value', $value, $field );
			set_theme_mod( $id, $value );
		}
		do_action( 'fitwp_options_save' );
		die;
	}

	/**
	 * Reset theme options
	 *
	 * @return void
	 */
	function reset()
	{
		check_ajax_referer( 'reset' );
		foreach ( FitWP_Options::$fields as $id => $field )
		{
			remove_theme_mod( $id );
		}
		do_action( 'fitwp_options_save' );
		do_action( 'fitwp_options_reset' );
		die;
	}

	/**
	 * Import theme options
	 *
	 * @return void
	 */
	function import()
	{
		check_ajax_referer( 'import' );
		$data = maybe_unserialize( base64_decode( $_POST['data'] ) );
		foreach ( $data as $k => $v )
		{
			set_theme_mod( $k, $v );
		}
		do_action( 'fitwp_options_save' );
		do_action( 'fitwp_options_import' );
		die;
	}
}

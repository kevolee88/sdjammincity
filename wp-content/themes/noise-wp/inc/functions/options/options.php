<?php
/**
 * FitWP Options Framework
 *
 * @author    The FitWP Team <anh@fitwp.com>
 * @copyright Copyright (c) 2013 by fitwp.com
 * @version   1.0
 */

/**
 * Framework loader
 *
 * @package   FitWP Options Framework
 * @author    The FitWP Team <anh@fitwp.com>
 * @copyright Copyright (c) 2013 by fitwp.com
 * @version   1.0
 */
class FitWP_Options
{
	/**
	 * Store theme options config
	 *
	 * @var array
	 */
	static $sections;

	/**
	 * Store fields config
	 *
	 * @var array
	 */
	static $fields = array();

	/**
	 * Initialize framework
	 *
	 * @return void
	 */
	static function init()
	{
		// Define theme options config via filter
		$sections = apply_filters( 'fitwp_options', null );
		if ( empty( $sections ) )
			return;

		self::constants();
		self::load_textdomain();
		self::load_files();

		foreach ( $sections as &$section )
		{
			self::normalize( $section );

			if ( isset( $section['fields'] ) )
				self::$fields = array_merge( self::$fields, self::get_fields( $section['fields'] ) );
		}
		self::$sections = $sections;

		if ( is_admin() )
			new FitWP_Options_Admin( $sections );
	}

	/**
	 * Define framework constants
	 *
	 * @see http://www.deluxeblogtips.com/2013/07/get-url-of-php-file-in-wordpress.html
	 *
	 * @return void
	 */
	static function constants()
	{
		// See: http://www.deluxeblogtips.com/2013/07/get-url-of-php-file-in-wordpress.html
		$content_url = untrailingslashit( dirname( dirname( get_stylesheet_directory_uri() ) ) );
		$content_dir = untrailingslashit( dirname( dirname( get_stylesheet_directory() ) ) );
		$content_dir = str_replace( '\\', '/', $content_dir );

		$dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
		$url = trailingslashit( str_replace( $content_dir, $content_url, $dir ) );

		define( 'FITWP_OPTIONS_DIR', $dir );
		define( 'FITWP_OPTIONS_URL', $url );
	}

	/**
	 * Load framework translation
	 *
	 * @return void
	 * @since 1.0
	 */
	static function load_textdomain()
	{
		load_textdomain( 'fitwp', trailingslashit( FITWP_OPTIONS_DIR . 'lang' ) . get_locale() . '.mo' );
	}

	/**
	 * Load framework files
	 *
	 * @return void
	 * @since 1.0
	 */
	static function load_files()
	{
		if ( !is_admin() )
			return;
		include FITWP_OPTIONS_DIR . 'inc/helper.php';
		include FITWP_OPTIONS_DIR . 'inc/admin.php';
		include FITWP_OPTIONS_DIR . 'inc/fields.php';
	}

	/**
	 * Get all fields config
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	static function get_fields( $fields )
	{
		$return = array();
		foreach ( $fields as $field )
		{
			if ( !is_array( $field ) )
				continue;
			if ( isset( $field['children'] ) )
				$return = array_merge( $return, self::get_fields( $field['children'] ) );
			elseif ( isset( $field['id'] ) )
				$return[$field['id']] = $field;
		}

		return $return;
	}

	/**
	 * Normalize section
	 *
	 * @param array $section
	 *
	 * @return void
	 */
	static function normalize( &$section )
	{
		// Default values for section
		$section = wp_parse_args( $section, array(
			'title'  => '',
			'icon'   => '',
			'level'  => 0,
			'fields' => array(),
		) );

		// Default value for fields
		// foreach ( $section['fields'] as &$field )
		// {
		// 	$field = wp_parse_args( $field, array(
		// 		'id'         => '',
		// 		'label'      => '',
		// 		'type'       => '',
		// 		'label_desc' => '',
		// 		'input_desc' => '',
		// 		'suffix'     => '',
		// 		'default'    => '',
		// 	) );

		// 	// Allow to normalize field
		// 	// $field = fitwp_options_filter( 'normalize_field', $field, $field );
		// }
	}
}

add_action( 'init', array( 'FitWP_Options', 'init' ) );

/**
 * Get theme option value
 *
 * @param  string $name Option name
 *
 * @return mixed
 */
function fitwp_option( $name )
{
	$default = isset( FitWP_Options::$fields[$name] ) && isset( FitWP_Options::$fields[$name]['default'] ) ? FitWP_Options::$fields[$name]['default'] : false;
	return get_theme_mod( $name, $default );
}

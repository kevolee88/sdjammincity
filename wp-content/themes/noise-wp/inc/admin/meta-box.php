<?php
/**
* Registering meta boxes
*
* All the definitions of meta boxes are listed below with comments.
* Please read them CAREFULLY.
*
* You also should read the changelog to know what has been changed before updating.
*
* For more information, please visit:
* @link http://www.deluxeblogtips.com/meta-box/
*/

add_filter( 'rwmb_meta_boxes', 'noise_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * @param array $meta_boxes
 *
 * @return array
 */
function noise_register_meta_boxes( $meta_boxes )
{
	// Post format gallery
	$meta_boxes[] = array(
		'title'  => __( 'Gallery', 'noise' ),
		'id'     => 'format-gallery',
		'pages'  => array( 'post' ),
		'fields' => array(
			array(
				'name' => __( 'Images', 'noise' ),
				'id'   => 'images',
				'type' => 'image_advanced',
			),
		),
	);

	// Post format link
	$meta_boxes[] = array(
		'title'  => __( 'Link', 'noise' ),
		'id'     => 'format-link',
		'pages'  => array( 'post' ),
		'fields' => array(
			array(
				'name' => __( 'Link', 'noise' ),
				'id'   => 'url',
				'type' => 'url',
				'desc' => __( 'Enter your url here', 'noise' ),
			),
		),
	);

	// Post format quote
	$meta_boxes[] = array(
		'title'  => __( 'Quote', 'noise' ),
		'id'     => 'format-quote',
		'pages'  => array( 'post' ),
		'fields' => array(
			array(
				'name' => __( 'Quote', 'noise' ),
				'id'   => 'quote',
				'type' => 'textarea',
				'desc' => __( 'Enter quote content', 'noise' ),
			),
			array(
				'name' => __( 'Author', 'noise' ),
				'id'   => 'quote_author',
				'type' => 'text',
				'desc' => __( 'Enter quote author\'s name', 'noise' ),
			),
		),
	);

	// Post format video
	$meta_boxes[] = array(
		'title'  => __( 'Link', 'noise' ),
		'id'     => 'format-video',
		'pages'  => array( 'post' ),
		'fields' => array(
			array(
				'name' => __( 'Video', 'noise' ),
				'id'   => 'video',
				'type' => 'file_input',
				'desc' => __( 'Enter video url or upload or pick one from library', 'noise' ),
			),
		),
	);

	// Post format video
	$meta_boxes[] = array(
		'title'  => __( 'Audio', 'noise' ),
		'id'     => 'format-audio',
		'pages'  => array( 'post' ),
		'fields' => array(
			array(
				'name' => __( 'Audio', 'noise' ),
				'id'   => 'audio',
				'type' => 'file_input',
				'desc' => __( 'Enter audio url or upload or pick one from library', 'noise' ),
			),
			array(
				'name' => __( 'Spectrum', 'noise' ),
				'id'   => 'spectrum',
				'type' => 'checkbox',
				'desc' => __( 'Play this audio with spectrum', 'noise' ),
			),
		),
	);

	// Page title description
	$meta_boxes[] = array(
		'title'  => __( 'Blog Description', 'noise' ),
		'id'     => 'blog-desc',
		'pages'  => array( 'page' ),
		'fields' => array(
			array(
				'name' => __( 'Blog Description', 'noise' ),
				'id'   => 'title_desc',
				'type' => 'text',
				'desc' => __( 'Enter description for blog title', 'noise' ),
			),
		),
	);

	// Display Page title
	$meta_boxes[] = array(
		'title'  => __( 'Page Setting', 'noise' ),
		'id'     => 'page-title',
		'pages'  => array( 'page' ),
		'fields' => array(
			array(
				'id'       => 'hide_title',
				'name'     => __( 'Hide Page Title', 'noise' ),
				'desc'     => __( 'Hide this page title in the frontend', 'noise' ),
				'type'     => 'checkbox',
			),
			array(
				'id'       => 'show_map',
				'name'     => __( 'Show map', 'noise' ),
				'desc'     => __( 'Show map at the bottom of page', 'noise' ),
				'type'     => 'checkbox',
			),
		),
	);

	// Under construction template
	$meta_boxes[] = array(
		'title'  => __( 'Under construction', 'noise' ),
		'id'     => 'under-construction',
		'pages'  => array( 'page' ),
		'fields' => array(
			array(
				'name' => __( 'Under Construction Notice', 'noise' ),
				'id'   => 'uc_title',
				'type' => 'text',
				'desc' => __( 'Write something to notice visitor that your site is under construction. If you leave empty, page title will be used be default.', 'noise' ),
			),
			array(
				'name' => __( 'Under construction Description', 'noise' ),
				'id'   => 'uc_desc',
				'type' => 'text',
				'desc' => __( 'More information about your notice', 'noise' ),
			),
			array(
				'name' => __( 'Ending date', 'noise' ),
				'id'   => 'uc_endtime',
				'type' => 'datetime',
				'desc' => __( 'Select the date when your site is complete', 'noise' ),
				'js_options' => array(
					'appendText'      => __( '(yyyy-mm-dd hh:ii)', 'noise' ),
					'dateFormat'      => __( 'yy-mm-dd', 'noise' ),
					'changeMonth'     => true,
					'changeYear'      => true,
					'showButtonPanel' => true,
				),
			),
			array(
				'name' => __( 'Background Image', 'noise' ),
				'id'   => 'uc_bg',
				'type' => 'image_advanced',
				'desc' => __( 'Upload background image for under construction page', 'noise' ),
				'max_file_uploads' => 1,
			),
		),
	);

	// Artist
	$meta_boxes[] = array(
		'title' => __( 'Artist Information', 'noise' ),
		'pages' => array( 'artist' ),
		'fields' => array(
			array(
				'name'  => __( 'Featured Artist', 'noise' ),
				'id'    => '_featured_artist',
				'type'  => 'checkbox',
				'desc'  => __( 'Featured artists can be displayed on Onpage when allowed in Theme Options', 'noise' ),
			),
			array(
				'name'  => __( 'Full Name', 'noise' ),
				'id'    => 'full_name',
				'type'  => 'text',
			),
			array(
				'name'  => __( 'Nick Name', 'noise' ),
				'id'    => 'nick_name',
				'type'  => 'text',
			),
			array(
				'name' => __( 'Date Of Birth', 'noise' ),
				'id'   => 'date_of_birth',
				'type' => 'date',

				'js_options' => array(
					'appendText'      => __( '(yyyy/mm/dd)', 'noise' ),
					'dateFormat'      => 'yy/mm/dd',
					'changeMonth'     => true,
					'changeYear'      => true,
					'showButtonPanel' => true,
					'yearRange'       => '1900:' . date('Y'),
				),
			),
			array(
				'name' => __( 'Place Of Birth', 'noise' ),
				'id'   => 'place_of_birth',
				'type' => 'text',
			),
			array(
				'name' => __( 'Occupation', 'noise' ),
				'id'   => 'occupation',
				'type' => 'text',
			),
			array(
				'name' => __( 'Best Known For', 'noise' ),
				'id'   => 'best_known',
				'type' => 'textarea',
				'rows' => 5,
			),
			array(
				'name' => __( 'Synopsis', 'noise' ),
				'id'   => 'synopsis',
				'type' => 'textarea',
			),
			array(
				'name' => __( 'Facebook Link', 'noise' ),
				'id'   => 'facebook',
				'type' => 'url',
			),
			array(
				'name' => __( 'Twitter Link', 'noise' ),
				'id'   => 'twitter',
				'type' => 'url',
			),
			array(
				'name' => __( 'SoundCloud Link', 'noise' ),
				'id'   => 'soundcloud',
				'type' => 'text',
			),
			array(
				'name' => __( 'Google Plus Link', 'noise' ),
				'id'   => 'google_plus',
				'type' => 'url',
			),
			array(
				'name' => __( 'Vimeo Link ', 'noise' ),
				'id'   => 'vimeo',
				'type' => 'url',
			),
			array(
				'name' => __( 'Homepage Link ', 'noise' ),
				'id'   => 'homepage_artist',
				'type' => 'url',
			),
			array(
				'name' => __( 'Images', 'noise' ),
				'id'   => 'images',
				'type' => 'image_advanced',
			),
		),
	);

	// Track
	$meta_boxes[] = array(
		'title' => __( 'Track Information', 'noise' ),
		'pages' => array( 'track' ),
		'fields' => array(
			array(
				'name'  => __( 'Featured Track', 'noise' ),
				'id'    => '_featured_track',
				'type'  => 'checkbox',
				'desc'  => __( 'Featured artists can be displayed on Widget Noise - Featured Track', 'noise' ),
			),
			array(
				'name'  => __( 'Show Download Button', 'noise' ),
				'id'    => 'show_download',
				'type'  => 'checkbox',
				'desc'  => __( 'Allow hide/show download button', 'noise' ),
				'std'	=> fitwp_option( 'show_download' ),
			),
			array(
				'name'  => __( 'Hide track in Lastest Tracks section', 'noise' ),
				'id'    => 'hide_on_latest_section',
				'type'  => 'checkbox',
				'desc'  => __( 'Allow hide track in Lastest Tracks section', 'noise' ),
			),
			array(
				'name' => __( 'Album', 'noise' ),
				'id'   => 'album',
				'type' => 'post',

				// Post type
				'post_type'  => 'album',
				'field_type' => 'select_advanced',
				'query_args' => array(
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				)
			),
			array(
				'name' => __( 'Artist', 'noise' ),
				'id'   => 'artist',
				'type' => 'post',

				// Post type
				'post_type' => 'artist',
				'field_type' => 'select_advanced',
				'query_args' => array(
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				)
			),
			array(
				'name' => __( 'Composer', 'noise' ),
				'id'   => 'composer',
				'type' => 'text',
			),
			array(
				'name' => __( 'Release Date', 'noise' ),
				'id'   => 'date',
				'type' => 'date',
				'js_options' => array(
					'appendText'      => __( '(yyyy/mm/dd)', 'noise' ),
					'dateFormat'      => 'yy/mm/dd',
					'changeMonth'     => true,
					'changeYear'      => true,
					'showButtonPanel' => true,
				),
			),
			array(
				'name' => __( 'Genre', 'noise' ),
				'id'   => 'genre',
				'type' => 'text',
			),
			array(
				'name' => __( 'Length', 'noise' ),
				'id'   => 'length',
				'type' => 'time',
				'js_options' => array(
					'stepMinute' => 1,
					'showSecond' => true,
					'stepSecond' => 1,
				),
			),
			array(
				'name'  => __( 'Track Upload', 'noise' ),
				'id'    => 'upload',
				'type'  => 'file_input',
			),
			array(
				'name' => __( 'SoundCloud URL', 'noise' ),
				'id'   => 'soundcloud',
				'type' => 'url',
			),
			array(
				'name' => __( 'Lyrics', 'noise' ),
				'id'   => 'lyrics',
				'type' => 'textarea',
				'rows' => 5,
			),
			array(
				'name'  => __( 'Videos', 'noise' ),
				'id'    => 'videos',
				'type'  => 'file_input',
				'clone' => true,
			),
			array(
				'name' => __( 'Images', 'noise' ),
				'id'   => 'images',
				'type' => 'image_advanced',
				'desc' => __( 'Best size for image is 1150x540px. You can use images with bigger resolution.', 'noise' ),
			),
			array(
				'name' => __( 'Cover Image', 'noise' ),
				'id'   => 'cover',
				'type' => 'image_advanced',
				'max_file_uploads' => 1,
				'desc' => __( 'This cover image will be displayed on extended player. This is optional. If no image is set, the first image in gallery will be used.', 'noise' ),
			),
			array(
				'name'  => __( 'Views', 'noise' ),
				'id'    => 'views',
				'type'  => 'hidden',
				'std'   => 0,
			),
			array(
				'name'  => __( 'Views Weekly', 'noise' ),
				'id'    => 'views_week',
				'type'  => 'hidden',
				'std'   => 0,
			),
			array(
				'name'  => __( 'Views Monthly', 'noise' ),
				'id'    => 'views_month',
				'type'  => 'hidden',
				'std'   => 0,
			),
			array(
				'name'  => __( 'Votes', 'noise' ),
				'id'    => 'votes',
				'type'  => 'hidden',
				'std'   => 0,
			),
			array(
				'name'  => __( 'Number Of Votes', 'noise' ),
				'id'    => 'votes_num',
				'type'  => 'hidden',
				'std'   => 0,
			),
			array(
				'name'  => __( 'Votes Weekly', 'noise' ),
				'id'    => 'votes_week',
				'type'  => 'hidden',
				'std'   => 0,
			),
			array(
				'name'  => __( 'Number Of Votes In Week', 'noise' ),
				'id'    => 'votes_week_num',
				'type'  => 'hidden',
				'std'   => 0,
			),
			array(
				'name'  => __( 'Votes Monthly', 'noise' ),
				'id'    => 'votes_month',
				'type'  => 'hidden',
				'std'   => 0,
			),
			array(
				'name'  => __( 'Number Of Votes In Month', 'noise' ),
				'id'    => 'votes_month_num',
				'type'  => 'hidden',
				'std'   => 0,
			)
		),
	);

	// Event
	$meta_boxes[] = array(
		'title' => __( 'Event Information', 'noise' ),
		'pages' => array( 'event' ),
		'fields' => array(
			array(
				'name' => __( 'Event Datetime', 'noise' ),
				'id'   => 'datetime',
				'type' => 'datetime',

				'js_options' => array(
					'stepMinute'     => 15,
					'showTimepicker' => true,
				),
			),

			array(
				'name' => __( 'Information', 'noise' ),
				'id'   => 'info',
				'type' => 'textarea',
			),
			array(
				'name'  => __( 'Place', 'noise' ),
				'id'    => 'place',
				'type'  => 'text',
			),
			array(
				'name'    => __( 'Status', 'noise' ),
				'id'      => 'status',
				'type'    => 'radio',
				'options' => array(
					'buy'      => __( 'Available', 'noise' ),
					'sold_out' => __( 'Sold Out', 'noise' ),
					'cancel'   => __( 'Canceled', 'noise' ),
					'free'     => __( 'Free', 'noise' ),
				),
				'std' => 'buy'
			),
			array(
				'name'  => __( 'Buy Ticket Link', 'noise' ),
				'id'    => 'buy_ticket',
				'type'  => 'url',
			),
			array(
				'name' => __( 'Event Images', 'noise' ),
				'id'   => 'images',
				'type' => 'image_advanced',
				'desc' => __( 'Best size for image is 1150x540px. You can use images with bigger resolution.', 'noise' ),
			),
			array(
				'name'  => __( 'Event Video', 'noise' ),
				'id'    => 'videos',
				'type'  => 'file_input',
				'clone' => true,
			),
		),
	);

	// Album
	$meta_boxes[] = array(
		'title' => __( 'Album Information', 'noise' ),
		'pages' => array( 'album' ),
		'fields' => array(
			array(
				'name' => __( 'Date', 'noise' ),
				'id'   => 'date',
				'type' => 'date',
				'js_options' => array(
					'appendText'      => __( '(yyyy/mm/dd)', 'noise' ),
					'dateFormat'      => 'yy/mm/dd',
					'changeMonth'     => true,
					'changeYear'      => true,
					'showButtonPanel' => true,
				),
			),
			array(
				'name'  => __( 'Composer', 'noise' ),
				'id'    => 'composer',
				'type'  => 'text',
			),
			array(
				'name'  => __( 'Genre', 'noise' ),
				'id'    => 'genre',
				'type'  => 'text',
			),
			array(
				'name'  => __( 'Itunes Link', 'noise' ),
				'id'    => 'itunes',
				'type'  => 'text',
			),
			array(
				'name'  => __( 'Amazon Link', 'noise' ),
				'id'    => 'amazon',
				'type'  => 'text',
			),
			array(
				'name'  => __( 'Spotify', 'noise' ),
				'id'    => 'spotify',
				'type'  => 'text',
			),
			array(
				'name' => __( 'Review', 'noise' ),
				'id'   => 'realse_review',
				'type' => 'wysiwyg',
				'raw'  => false,
				'options' => array(
					'textarea_rows' => 4,
					'teeny'         => true,
					'media_buttons' => false,
				),
			),
		),
	);

	// Gallery
	$meta_boxes[] = array(
		'title' => __( 'Gallery Information', 'noise' ),
		'pages' => array( 'gallery' ),
		'fields' => array(
			array(
				'name' => __( 'Type', 'noise' ),
				'id'   => 'type',
				'type' => 'radio',
				'options' => array(
					'photos' => __( 'Photos', 'noise' ),
					'videos' => __( 'Videos', 'noise' ),
				),
				'std'  => 'photos',
			),
			array(
				'name' => __( 'Gallery Images', 'noise' ),
				'id'   => 'images',
				'type' => 'image_advanced',
				'desc' => __( 'Best size for image is 1150x540px. You can use images with bigger resolution.', 'noise' ),
			),
			array(
				'name'  => __( 'Gallery Video', 'noise' ),
				'id'    => 'videos',
				'type'  => 'file_input',
				'clone' => true,
			),
		),
	);

    return $meta_boxes;
}

add_filter( 'manage_nav-menus_columns', 'noise_add_menus_meta_box' );

/**
 * Add custom menu meta boxes
 *
 * @since 1.0
 * @param  mixed $func
 * @return mixed
 */
function noise_add_menus_meta_box( $func )
{
	add_meta_box( 'noise-sections', __( 'Noise Sections', 'noise' ), 'noise_menu_box', 'nav-menus', 'side' );
	wp_enqueue_script( 'noise-menu', THEME_URL . 'js/admin/menu.js', array( 'nav-menu' ), '', true );

	return $func;
}

/**
 * Add custom menu box
 *
 * @since 1.0
 * @return void
 */
function noise_menu_box()
{
	global $_nav_menu_placeholder, $nav_menu_selected_id;
	$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;

	$sections = array(
		'section-slider'           => __( 'Slider', 'noise' ),
		'section-latest-tracks'    => __( 'Latest Tracks', 'noise' ),
		'section-like'             => __( 'Like & Follow', 'noise' ),
		'section-popular-tracks'   => __( 'Popular Tracks', 'noise' ),
		'section-testimonials'     => __( 'Testimonials', 'noise' ),
		'section-media'            => __( 'Media', 'noise' ),
		'section-latest-tweets'    => __( 'Tweets', 'noise' ),
		'section-releases'         => __( 'Releases', 'noise' ),
		'section-subscribe'        => __( 'Subscribe', 'noise' ),
		'section-events'           => __( 'Events', 'noise' ),
		'section-countdown'        => __( 'Countdown', 'noise' ),
		'section-artists'          => __( 'DJ & Band', 'noise' ),
		'section-connect'          => __( 'Contact', 'noise' ),
		'section-blog'             => __( 'Blog', 'noise' ),
		'section-latest-news'      => __( 'Latest News', 'noise' ),
		'section-shop'             => __( 'Shop', 'noise' ),
		'section-products'         => __( 'Products', 'noise' ),
		'section-custom-content'   => __( 'Custom Content', 'noise' ),
		'section-custom-content-2' => __( 'Custom Content 2', 'noise' ),
	);

	echo '<ul id="noise-sections">';

	foreach ( $sections as $id => $title )
	{
		printf(
			'<li>
				<label><input type="checkbox" name="menu-item[%1$s][menu-item-object-id]" value="%2$s"> %3$s</label>
				<input type="hidden" class="menu-item-title" name="menu-item[%1$s][menu-item-title]" value="%3$s">
				<input type="hidden" class="menu-item-url" name="menu-item[%1$s][menu-item-url]" value="%4$s/#%2$s">
			</li>',
			$_nav_menu_placeholder,
			$id,
			$title,
			untrailingslashit( home_url() )
		);
	}
	echo '</ul>';
	?>
	<p class="button-controls">
		<span class="add-to-menu">
			<input id="submit-noise-sections" type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'noise' ); ?>">
			<span class="spinner"></span>
		</span>
	</p>
	<?php
}

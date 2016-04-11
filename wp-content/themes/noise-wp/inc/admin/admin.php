<?php
add_action( 'admin_enqueue_scripts', 'noise_admin_enqueue_scripts' );

/**
 * Enqueue scripts for admin
 * @return void
 * @since  1.0
 */
function noise_admin_enqueue_scripts()
{
	$screen = get_current_screen();
	if ( in_array( $screen->post_type, array( 'page', 'post' ) ) || 'appearance_page_theme-options' == $screen->id )
		wp_enqueue_script( 'noise-admin', THEME_URL . 'js/admin/admin.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0', true );
}

add_action( 'admin_head-nav-menus.php', 'noise_remove_metabox_nav' );

/**
* Remove metabox from menu editor page
*
* @since 1.0
*/
function noise_remove_metabox_nav()
{
    remove_meta_box( 'add-track', 'nav-menus', 'side' );
    remove_meta_box( 'add-artist', 'nav-menus', 'side' );
    remove_meta_box( 'add-album', 'nav-menus', 'side' );
    remove_meta_box( 'add-event', 'nav-menus', 'side' );
    remove_meta_box( 'add-gallery', 'nav-menus', 'side' );
}

add_filter( 'cron_schedules', 'noise_add_schedules_intervals' );

/**
 * Add schedules intervals
 *
 * @since  1.0
 * @param  array $schedules
 * @return array
 */
function noise_add_schedules_intervals( $schedules )
{
	$schedules['weekly'] = array(
		'interval' => 604800,
		'display' => __( 'Once Weekly', 'noise' )
	);

	$schedules['monthly'] = array(
		'interval' => 2635200,
		'display' => __( 'Once a month', 'noise' )
	);

	return $schedules;
}

add_action( 'after_switch_theme', 'noise_add_schedule_events' );

/**
 * Add scheduled event during theme activation
 *
 * @since  1.0
 * @return void
 */
function noise_add_schedule_events()
{
	if ( !wp_next_scheduled( 'noise_reset_track_data_weekly' ) )
		wp_schedule_event( time(), 'weekly', 'noise_reset_track_data_weekly' );

	if ( !wp_next_scheduled( 'noise_reset_track_data_monthly' ) )
		wp_schedule_event( time(), 'monthly', 'noise_reset_track_data_monthly' );
}

add_action( 'switch_theme', 'noise_remove_schedule_events' );

/**
 * Remove scheduled events when theme deactived
 *
 * @since  1.0
 * @return void
 */
function noise_remove_schedule_events()
{
	wp_clear_scheduled_hook( 'noise_reset_track_data_weekly' );
	wp_clear_scheduled_hook( 'noise_reset_track_data_monthly' );
}

add_action( 'noise_reset_track_data_weekly', 'noise_reset_week_votes' );

/**
 * Reset vote counter of week
 *
 * @since  1.0
 * @return void
 */
function noise_reset_week_votes()
{
	// Top 3 of week
	$top_week = get_posts( array(
		'post_type'   => 'track',
		'numberposts' => 3,
		'orderby'     => 'meta_value_num',
		'meta_key'    => 'votes_week',
	) );
	$tops = array();
	foreach( $top_week as $t )
		$tops[$t->ID] = array(
			'vote' => get_post_meta( $t->ID, 'votes_week', true ),
			'num'  => get_post_meta( $t->ID, 'votes_week_num', true ),
		);

	$tracks = get_posts( array( 'numberposts' => -1, 'post_type' => 'track' ) );

	// fetch 20 posts at a time rather than loading the entire table into memory
	while ( $next_tracks = array_splice( $tracks, 0, 20 ) )
	{
		foreach( $next_tracks as $track )
		{
			$votes = 0;
			$num = 0;
			if ( isset( $top[$track->ID] ) )
			{
				$reduce = rand( 0, 100 );
				$votes = max( 5, $top[$track->ID]['vote'] - $reduce );
				$num = max( 1, $top[$track->ID]['num'] - $reduce/5 );
			}

			update_post_meta( $track->ID, 'votes_week', $votes );
			update_post_meta( $track->ID, 'votes_week_num', $num );
		}
	}
}

add_action( 'noise_reset_track_data_monthly', 'noise_reset_month_votes' );

/**
 * Reset vote counter of month
 *
 * @since  1.0
 * @return void
 */
function noise_reset_month_votes()
{
	// Top 3 of week
	$top_month = get_posts( array(
		'post_type'   => 'track',
		'numberposts' => 3,
		'orderby'     => 'meta_value_num',
		'meta_key'    => 'votes_month',
	) );
	$tops = array();
	foreach( $top_month as $t )
		$tops[$t->ID] = array(
			'vote' => get_post_meta( $t->ID, 'votes_week', true ),
			'num'  => get_post_meta( $t->ID, 'votes_month_num', true ),
		);

	$tracks = get_posts( array( 'numberposts' => -1, 'post_type' => 'track' ) );

	// fetch 20 posts at a time rather than loading the entire table into memory
	while ( $next_tracks = array_splice( $tracks, 0, 20 ) )
	{
		foreach( $next_tracks as $track )
		{
			$votes = 0;
			$num = 0;
			if ( isset( $top[$track->ID] ) )
			{
				$reduce = rand( 0, 100 );
				$votes = max( 5, $top[$track->ID]['vote'] - $reduce );
				$num = max( 1, $top[$track->ID]['num'] - $reduce/5 );
			}

			update_post_meta( $track->ID, 'votes_month', $votes );
			update_post_meta( $track->ID, 'votes_month_num', $num );
		}
	}
}

add_action( 'tgmpa_register', 'noise_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * @since 1.0
 */
function noise_register_required_plugins()
{
	$plugins = array(
		array(
			'name'      => 'Meta Box',
			'slug'      => 'meta-box',
			'required'  => true,
		),
		array(
			'name'      => 'MailPoet Newsletters',
			'slug'      => 'wysija-newsletters',
			'required'  => false,
		),
	);

	$theme_text_domain = 'noise';

	$config = array(
		'domain'            => $theme_text_domain,
		'default_path'      => '',
		'parent_menu_slug'  => 'themes.php',
		'parent_url_slug'   => 'themes.php',
		'menu'              => 'install-required-plugins',
		'has_notices'       => true,
		'is_automatic'      => false,
		'message'           => '',
		'strings'           => array(
			'page_title'                      => __( 'Required Plugins', $theme_text_domain ),
			'menu_title'                      => __( 'Install Plugins', $theme_text_domain ),
			'installing'                      => __( 'Installing Plugin: %s', $theme_text_domain ),
			'oops'                            => __( 'Something went wrong with the plugin API.', $theme_text_domain ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ),
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ),
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ),
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                          => __( 'Return to Required Plugins Installer', $theme_text_domain ),
			'plugin_activated'                => __( 'Plugin activated successfully.', $theme_text_domain ),
			'complete'                        => __( 'All plugins installed and activated successfully. %s', $theme_text_domain )
		)
	);

	tgmpa( $plugins, $config );
}

add_filter( 'manage_artist_posts_columns', 'noise_artist_columns' );
add_action( 'manage_artist_posts_custom_column', 'noise_artist_custom_column_content', 10, 2 );

/**
 * Add custom columns to artists manage page
 *
 * @since  1.2.1
 * @param  array $columns
 * @return array
 */
function noise_artist_columns( $columns )
{
	$info_columns = array_splice( $columns, 2 );
	$columns['featured'] = __( 'Featured', 'noise' );

	$columns = array_merge( $columns, $info_columns );

	return $columns;
}

/**
 * Display custom column content for artists
 *
 * @since  1.2.1
 * @param  string $column
 * @param  int    $post_id
 * @return void
 */
function noise_artist_custom_column_content( $column, $post_id )
{
	if ( 'featured' == $column )
	{
		$featured = get_post_meta( $post_id, '_featured_artist', true );
		printf(
			'<a href="%s" title="%s" class="noise-toggle-featured"><span class="%s dashicons-before"></span></a>',
			add_query_arg(
				array(
					'action'    => 'noise_feature_artist',
					'artist_id' => $post_id,
					'_wpnonce'  => wp_create_nonce( 'noise_feature_artist' . $post_id )
				),
				admin_url( 'admin-ajax.php' )
			),
			__( 'Toggle Featured', 'noise' ),
			$featured ? 'dashicons-star-filled' : 'dashicons-star-empty'
		);
		echo '<span class="spinner" style="float: left;"></span>';
	}
}

add_filter( 'manage_track_posts_columns', 'noise_track_columns' );
add_action( 'manage_track_posts_custom_column', 'noise_track_custom_column_content', 10, 2 );

/**
 * Add custom columns to artists manage page
 *
 * @since  2.2.2
 * @param  array $columns
 * @return array
 */
function noise_track_columns( $columns )
{
	$info_columns = array_splice( $columns, 2 );
	$columns['featured'] = __( 'Featured', 'noise' );

	$columns = array_merge( $columns, $info_columns );

	return $columns;
}

/**
 * Display custom column content for artists
 *
 * @since  2.2.2
 * @param  string $column
 * @param  int    $post_id
 * @return void
 */
function noise_track_custom_column_content( $column, $post_id )
{
	if ( 'featured' == $column )
	{
		$featured = get_post_meta( $post_id, '_featured_track', true );
		printf(
			'<a href="%s" title="%s" class="noise-toggle-featured"><span class="%s dashicons-before"></span></a>',
			add_query_arg(
				array(
					'action'    => 'noise_feature_track',
					'track_id' => $post_id,
					'_wpnonce'  => wp_create_nonce( 'noise_feature_track' . $post_id )
				),
				admin_url( 'admin-ajax.php' )
			),
			__( 'Toggle Featured', 'noise' ),
			$featured ? 'dashicons-star-filled' : 'dashicons-star-empty'
		);
		echo '<span class="spinner" style="float: left;"></span>';
	}
}

add_action( 'save_post', 'noise_update_exclude_tracks', 100 );

/**
 * Allow Show/hide track in lastest track section
 *
 * @since  2.2.4
 * @param  int   $post_id The ID of the post
 * @return void
 */
function noise_update_exclude_tracks( $post_id )
{
	if ( !isset( $_POST['post_type'] ) || 'track' != $_POST['post_type'] )
        return;

	// Check if this post is excluded from latest tracks section
	$latest_hidden   = isset( $_POST['hide_on_latest_section'] );
	$excluded_tracks = get_option( 'latest_tracks_exclude' );
	$excluded_tracks = $excluded_tracks ? $excluded_tracks : array();

	// Add to excluded_tracks option
	if ( $latest_hidden && !in_array( $post_id, $excluded_tracks ) ) {
		$excluded_tracks[] = $post_id;
	}
	// Remove from excluded_tracks option
	elseif ( !$latest_hidden && in_array( $post_id, $excluded_tracks ) )
	{
		$excluded_tracks = array_diff( $excluded_tracks, array( $post_id ) );
	}

	update_option( 'latest_tracks_exclude', $excluded_tracks );
}

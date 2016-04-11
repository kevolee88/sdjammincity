<?php
add_filter( 'fitwp_options', 'noise_options' );

/**
 * Register theme options
 *
 * @return array
 * @since  1.0
 */
function noise_options()
{
	$options = array();

	// General
	$options[] = array(
		'icon'   => 'cog-outline',
		'title'  => __( 'General', 'noise' ),
		'fields' => array(
			array(
				'id'      => 'preloader',
				'label'   => __( 'Preloader', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'uc_mode',
				'label'      => __( 'Under Construction Mode', 'noise' ),
				'type'       => 'switcher',
				'default'    => false,
				'label_desc' => __( 'Switch your site to under construction mode.', 'noise' ),
				'input_desc' => __( 'You have to create a page using template "Under Construction" to make this mode work correctly.', 'noise' ),
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'autoplay',
				'label'      => __( 'Auto Play', 'noise' ),
				'type'       => 'switcher',
				'default'    => false,
				'label_desc' => __( 'Allow player to auto start playing after website is loaded', 'noise' ),
			),
			array(
				'id'         => 'extended_player',
				'label'      => __( 'Extended Player', 'noise' ),
				'type'       => 'switcher',
				'default'    => true,
				'label_desc' => __( 'Allow show/hide extended player', 'noise' ),
			),
			array(
				'id'         => 'intro_audio',
				'label'      => __( 'Intro Audio', 'noise' ),
				'type'       => 'switcher',
				'default'    => false,
				'label_desc' => __( 'Play a track as the intro audio when website is loaded. Remember to enable Auto Play above.', 'noise' ),
			),
			array(
				'id'         => 'intro_track',
				'label'      => __( 'Intro Track', 'noise' ),
				'type'       => 'post',
				'label_desc' => __( 'Select a track to play as intro', 'noise' ),
				'args'       => array(
					'post_type' => 'track',
				),
			),
			array(
				'id'         => 'show_download',
				'label'      => __( 'Show Download Button', 'noise' ),
				'type'       => 'switcher',
				'default'    => true,
				'label_desc' => __( 'Display download button on expanded player. These settings may be overridden for individual tracks', 'noise' ),
			),
			array(
				'id'         => 'vote_timeout',
				'label'      => __( 'Track Vote Timeout', 'noise' ),
				'type'       => 'number',
				'suffix'     => __( 'days', 'noise' ),
				'default'    => 30,
				'label_desc' => __( 'Number of days a vistor can vote a track again', 'noise' ),
				'input_desc' => __( 'Set to 0 to allow visitors vote anytime', 'noise' ),
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'      => 'extended_player_img',
				'label'   => __( 'Cover Image of Extended Player', 'noise' ),
				'type'    => 'toggle',
				'default' => 'artist_image',
				'options' => array(
					'artist_image' => __( 'Artist Image', 'noise' ),
					'album_image' => __( 'Album Image', 'noise' ),
				),
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'favicon',
				'label'      => __( 'Favicon', 'noise' ),
				'type'       => 'image',
				'label_desc' => sprintf( __( 'Specify a <a href="%s" target="_blank">favicon</a> for your site. Accepted formats: .ico, .png, .gif.', 'noise' ), 'http://en.wikipedia.org/wiki/Favicon' ),
			),
			array(
				'id'         => 'touch_icon',
				'label'      => __( 'Touch Icon', 'noise' ),
				'type'       => 'image',
				'label_desc' => sprintf( __( 'Specify icon for mobile devices and tablets. <a href="%s" target="_blank">Learn more</a>.', 'noise' ), 'http://mathiasbynens.be/notes/touch-icons' ),
			),
		),
	);

	// Design
	$options[] = array(
		'icon'  => 'device-desktop',
		'title' => __( 'Design', 'noise' ),
	);

	// Color scheme
	$options[] = array(
		'title'  => __( 'Color Scheme', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'color_scheme',
				'label'   => __( 'Color Scheme', 'noise' ),
				'type'    => 'color_scheme',
				'options' => array(
					'green-dark',
					'red',
					'blue',
					'orange',
					'violet',
					'blue-sky-deep',
					'sapphire',
				),
				'default' => 'green-dark',
			),
			array(
				'type' => 'divider',
			),
			array(
				'label' => __( 'Custom Color Scheme', 'noise' ),
				'type'  => 'switcher',
				'id'    => 'custom_color_scheme',
			),
			array(
				'label'    => '&nbsp;',
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'custom_color_1',
						'type'       => 'color',
						'default'    => '#495745',
						'input_desc' => __( 'Primary Color', 'noise' ),
					),
					array(
						'id'         => 'custom_color_2',
						'type'       => 'color',
						'default'    => '#383838',
						'input_desc' => __( 'Secondary Color', 'noise' ),
					),
					array(
						'type'     => 'box',
						'box_type' => 'info',
						'text'     => __( '<b>Examples (default color schemes):</b><br>
							<i>Orange</i>: <code>#ff8800</code>, <code>#383838</code><br>
							<i>Green</i>: <code>#495745</code>, <code>#383838</code><br>
							<i>Blue</i>: <code>#1145af</code>, <code>#383838</code><br>
						', 'noise' ),
					),
				),
			),
		),
	);

	// Background
	$options[] = array(
		'title'  => __( 'Background', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'         => 'background',
				'label'      => __( 'Body Background', 'noise' ),
				'type'       => 'background',
				'label_desc' => sprintf( __( 'A lot of background patterns can be found at <a href="%s" target="_blank">Subtle Patterns</a>.', 'noise' ), 'http://subtlepatterns.com/' ),
			),
			array(
				'id'         => 'parallax_pattern',
				'label'      => __( 'Parallax Pattern', 'noise' ),
				'type'       => 'image',
				'label_desc' => __( 'The transparent pattern displayed over parallax background', 'noise' ),
			),
			array(
				'id'    => '404_background',
				'label' => __( '404 Page Background Image', 'noise' ),
				'type'  => 'image',
			),
		),
	);

	// Custom CSS
	$options[] = array(
		'title'  => __( 'Custom CSS', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'         => 'custom_css',
				'label'      => __( 'Custom CSS', 'noise' ),
				'type'       => 'textarea',
				'input_desc' => __( 'Enter your custom CSS here. This will overwrite theme default CSS.', 'noise' ),
			),
		),
	);

	// Header
	$options[] = array(
		'icon'   => 'th-large-outline',
		'title'  => __( 'Header', 'noise' ),
		'fields' => array(
			array(
				'id'         => 'logo',
				'label'      => __( 'Logo', 'noise' ),
				'type'       => 'image',
				'label_desc' => __( 'Specify logo URL or upload, select from Media Library.', 'noise' ),
			),
			array(
				'label'      => __( 'Logo Size (Optional)', 'noise' ),
				'label_desc' => __( 'Best size is 150x60', 'noise' ),
				'type'       => 'group',
				'children'   => array(
					array(
						'id'         => 'logo_width',
						'type'       => 'number',
						'input_desc' => __( 'Width', 'noise' ),
						'suffix'     => 'px',
					),
					array(
						'id'         => 'logo_height',
						'type'       => 'number',
						'input_desc' => __( 'Height', 'noise' ),
						'suffix'     => 'px',
					),
				)
			),
			array(
				'id'         => 'cart_on_menu',
				'label'      => __( 'Display Cart On Menu', 'noise' ),
				'type'       => 'switcher',
				'default'    => false,
				'label_desc' => __( 'Allow display cart icon on menu', 'noise' ),
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'header_scripts',
				'label'      => __( 'Header Scripts', 'noise' ),
				'type'       => 'textarea',
				'input_desc' => __( 'Enter scripts or code you would like output to <code>&lt;head&gt;</code>. It can be custom font link, meta tags, javascript, etc.', 'noise' ),
			),
		),
	);

	// Sections
	$options[] = array(
		'icon'  => 'document',
		'title' => __( 'Onepage Sections', 'noise' ),
	);

	// General
	$options[] = array(
		'title'  => __( 'Sections Order', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'         => 'sections',
				'label'      => __( 'Order Sections', 'noise' ),
				'type'       => 'sections',
				'label_desc' => __( 'Drag and drop sections to reorder. You can enable/disable each section in it\'s own setting menu on the left', 'noise' ),
				'section'    => array(
					'latest-tracks'    => __( 'Latest Tracks', 'noise' ),
					'like'             => __( 'Like And Follow', 'noise' ),
					'popular-tracks'   => __( 'Popular Tracks', 'noise' ),
					'testimonials'     => __( 'Quotes', 'noise' ),
					'media'            => __( 'Media', 'noise' ),
					'latest-tweets'    => __( 'Latest Tweets', 'noise' ),
					'releases'         => __( 'Releases', 'noise' ),
					'subscribe'        => __( 'Subscribe', 'noise' ),
					'events'           => __( 'Events', 'noise' ),
					'countdown'        => __( 'Special Event', 'noise' ),
					'artists'          => __( 'Artists', 'noise' ),
					'blog'             => __( 'Blog', 'noise' ),
					'latest-news'      => __( 'Latest News', 'noise' ),
					'shop'             => __( 'Shop', 'noise' ),
					'products'         => __( 'Products', 'noise' ),
					'connect'          => __( 'Connect', 'noise' ),
					'custom-content'   => __( 'Custom Content', 'noise' ),
					'custom-content-2' => __( 'Custom Content 2', 'noise' ),
				),
				'default'    => array(
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
					'blog',
					'latest-news',
					'connect',
				),
			),
		),
	);

	// Opener
	$options[] = array(
		'title'  => __( 'Opener', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'opener_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'id'    => 'opener_logo',
				'label' => __( 'Opener Logo', 'noise' ),
				'type'  => 'image',
			),
			array(
				'id'      => 'unlock_text',
				'label'   => __( 'Unlock Text', 'noise' ),
				'type'    => 'text',
				'size'    => 'xlarge',
				'default' => __( 'Slide To Unlock', 'noise' ),
			),
			array(
				'id'    => 'opener_bg',
				'label' => __( 'Background Image', 'noise' ),
				'type'  => 'image',
			),
		),
	);

	// Top slider
	$options[] = array(
		'title'  => __( 'Top Slider', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'slider_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'id'      => 'slider_type',
				'label'   => __( 'Background Type', 'noise' ),
				'type'    => 'toggle',
				'default' => 'static',
				'options' => array(
					'static' => __( 'Static Image', 'noise' ),
					'images' => __( 'Images Slider', 'noise' ),
					'video'  => __( 'Video', 'noise' ),
				),
			),
			array(
				'id'         => 'slider_parallax_image',
				'label'      => __( 'Background Image', 'noise' ),
				'class'      => 'static-bg-field',
				'type'       => 'image',
				'input_desc' => __( 'Upload parallax image for fullscreen parallax slider', 'noise' ),
			),
			array(
				'label'    => __( 'Background Video', 'noise' ),
				'class'    => 'video-bg-field',
				'type'     => 'group',
				'layout'   => 'vertical',
				'children' => array(
					array(
						'id'         => 'slider_video',
						'label'      => __( 'Video URL', 'noise' ),
						'type'       => 'text',
						'size'       => 'xlarge',
						'input_desc' => __( 'Direct video URL and Youtube URL are accepted', 'noise' ),
					),
					array(
						'id'         => 'slider_video_alt',
						'label'      => __( 'Alternative Video URL', 'noise' ),
						'type'       => 'text',
						'size'       => 'xlarge',
						'input_desc' => __( 'Alternative URL of direct video in other format', 'noise' ),
					),
					array(
						'type'     => 'box',
						'box_type' => 'info',
						'text'     => __( 'To make sure video plays on all browsers, you should enter both URLs to the video with formats <code>mp4 (h.264)</code> and <code>webm</code>', 'noise' ),
					),
				),
			),
			array(
				'id'      => 'auto_play_sound_video',
				'class'   => 'video-bg-field',
				'label'   => __( 'Auto Play Sound', 'noise' ),
				'type'    => 'switcher',
				'default' => false,
			),
			array(
				'id'      => 'button_sound_bg_video',
				'class'   => 'video-bg-field',
				'label'   => __( 'Show Volume Button', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'id'      => 'content_type',
				'class'   => 'images-bg-field static-bg-field',
				'label'   => __( 'Overlay Content', 'noise' ),
				'type'    => 'toggle',
				'default' => 'content_text',
				'options' => array(
					'content_text'   => __( 'Text Slider', 'noise' ),
					'content_custom' => __( 'Custom', 'noise' ),
				),
			),
			array(
				'type'     => 'group',
				'class'    => 'images-bg-field static-bg-field slider-field',
				'children' => array(
					array(
						'type'     => 'box',
						'box_type' => 'info',
						'text'     => __( 'Please enter text captions for the Top Slider. These captions will be displayed in a slider <b>over</b> image or video background. If you choose "Image Slider" above, please enter URLs for each sliding image below.', 'noise' )
					),
					array(
						'id'     => 'top_slider',
						'type'   => 'slider',
						'number' => 5,
					),
				)
			),
			array(
				'id'         => 'slider_content',
				'class'      => 'images-bg-field static-bg-field',
				'label'      => __( 'Content', 'noise' ),
				'type'       => 'editor',
				'input_desc' => __( 'You can insert logo, images or any text here. Shortcodes are allowed.', 'noise' ),
			),
		),
	);

	// Latest Tracks
	$options[] = array(
		'title'  => __( 'Latest Tracks', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'latest_tracks_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'label'    => __( 'Section Title', 'noise' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'latest_track_title',
						'type'       => 'text',
						'input_desc' => __( 'Title', 'noise' ),
						'default'    => __( 'New', 'noise' ),
					),
					array(
						'id'         => 'latest_track_suffix',
						'type'       => 'text',
						'input_desc' => __( 'Suffix', 'noise' ),
						'default'    => __( 's', 'noise' ),
					),
					array(
						'id'         => 'latest_track_subtitle',
						'type'       => 'text',
						'input_desc' => __( 'Subtitle', 'noise' ),
						'default'    => __( 'Latest Tracks', 'noise' ),
					),
				)
			),
			array(
				'id'         => 'latest_tracks_num',
				'label'      => __( 'Number Of Tracks', 'noise' ),
				'type'       => 'number',
				'default'    => 50,
				'label_desc' => __( 'Number of latest tracks to be displayed', 'noise' ),
				'input_desc' => __( 'Enter -1 if you want to display all tracks', 'noise' ),
			),
		),
	);

	// Like and Follow
	$options[] = array(
		'title'  => __( 'Like & Follow', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'like_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'id'    => 'like_bg',
				'label' => __( 'Background Image', 'noise' ),
				'type'  => 'image',
			),
			array(
				'id'         => 'social_message',
				'label'      => __( 'Message', 'noise' ),
				'type'       => 'textarea',
				'input_desc' => __( 'Can be written in multiple lines, odd lines will be in larger font size', 'noise' ),
			),
			array(
				'id'       => 'facebook_like',
				'label'    => __( 'Facebook Like Button', 'noise' ),
				'type'     => 'group',
				'layout'   => 'vertical',
				'children' => array(
					array(
						'id'      => 'facebook_like_text',
						'suffix'  => __( 'Button Label', 'noise' ),
						'type'    => 'text',
						'size'    => 'large',
						'default' => __( 'Like this on Facebook', 'noise' ),
					),
					array(
						'id'     => 'facebook_page',
						'suffix' => __( 'Facebook fanpage/group URL', 'noise' ),
						'type'   => 'text',
						'size'   => 'large',
					),
				)
			),
			array(
				'id'       => 'twitter_follow',
				'label'    => __( 'Twitter Follow Button', 'noise' ),
				'type'     => 'group',
				'layout'   => 'vertical',
				'children' => array(
					array(
						'id'      => 'twitter_follow_text',
						'suffix'  => __( 'Button Label', 'noise' ),
						'type'    => 'text',
						'size'    => 'large',
						'default' => __( 'Follow us on Twitter', 'noise' ),
					),
					array(
						'id'     => 'follow_user',
						'suffix' => __( 'Twitter Username', 'noise' ),
						'type'   => 'text',
						'size'   => 'large',
					),
				)
			),
		),
	);

	// Popular Tracks
	$options[] = array(
		'title'  => __( 'Popular Tracks', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'popular_tracks_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'label'    => __( 'Section Title', 'noise' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'popular_tracks_title',
						'type'       => 'text',
						'input_desc' => __( 'Title', 'noise' ),
						'default'    => __( 'The Best', 'noise' ),
					),
					array(
						'id'         => 'popular_tracks_suffix',
						'type'       => 'text',
						'input_desc' => __( 'Suffix', 'noise' ),
						'default'    => __( 's', 'noise' ),
					),
					array(
						'id'         => 'popular_tracks_subtitle',
						'type'       => 'text',
						'input_desc' => __( 'Subtitle', 'noise' ),
						'default'    => __( 'Popular Tracks', 'noise' ),
					),
				)
			),
			array(
				'id'         => 'popular_tracks_num',
				'label'      => __( 'Number Of Tracks', 'noise' ),
				'type'       => 'number',
				'default'    => 3,
				'label_desc' => __( 'Number of popular tracks to be displayed', 'noise' ),
			),
		),
	);

	// Testimonial
	$options[] = array(
		'title'  => __( 'Quotes', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'testimonials_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'id'      => 'testimonials_autoslide',
				'label'   => __( 'Auto Slide', 'noise' ),
				'type'    => 'switcher',
				'default' => false,
			),
			array(
				'id'    => 'testimonials_bg',
				'label' => __( 'Background Image', 'noise' ),
				'type'  => 'image',
			),
			array(
				'id'     => 'testimonials',
				'label'  => __( 'Quotes', 'noise' ),
				'type'   => 'testimonial',
				'number' => 5,
			),

		),
	);

	// Media
	$options[] = array(
		'title'  => __( 'Media', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'media_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'label'    => __( 'Section Title', 'noise' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'media_title',
						'type'       => 'text',
						'input_desc' => __( 'Title', 'noise' ),
						'default'    => __( 'Media', 'noise' ),
					),
					array(
						'id'         => 'media_suffix',
						'type'       => 'text',
						'input_desc' => __( 'Suffix', 'noise' ),
					),
					array(
						'id'         => 'media_subtitle',
						'type'       => 'text',
						'input_desc' => __( 'Subtitle', 'noise' ),
						'default'    => __( 'Photos', 'noise' ),
					),
				)
			),
			array(
				'id'         => 'media_photo_num',
				'label'      => __( 'Number Of Photos', 'noise' ),
				'type'       => 'number',
				'default'    => 50,
				'label_desc' => __( 'Number of photo galleris to be displayed', 'noise' ),
				'input_desc' => __( 'Enter -1 if you want to display all photo galleries', 'noise' ),
			),
			array(
				'id'         => 'media_video_num',
				'label'      => __( 'Number Of Videos', 'noise' ),
				'type'       => 'number',
				'default'    => 50,
				'label_desc' => __( 'Number of video galleries to be displayed', 'noise' ),
				'input_desc' => __( 'Enter -1 if you want to display all video galleries', 'noise' ),
			),
		),
	);

	// Twitter slider
	$options[] = array(
		'title'  => __( 'Latest Tweets', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'latest_tweets_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'id'      => 'tweets_autoslide',
				'label'   => __( 'Auto Slide', 'noise' ),
				'type'    => 'switcher',
				'default' => false,
			),
			array(
				'id'    => 'latest_tweets_bg',
				'label' => __( 'Background Image', 'noise' ),
				'type'  => 'image',
			),
			array(
				'box_type' => 'info',
				'type'     => 'box',
				'text'     => __( 'Please go to <a href="https://dev.twitter.com/apps">https://dev.twitter.com/apps</a> and create a new Twitter application to get the following information. See detailed instruction in the <a href="http://fitwp.github.io/docs/noise">documentation</a>.', 'noise' ),
			),
			array(
				'id'         => 'consumer_key',
				'label'      => __( 'Consumer Key', 'noise' ),
				'type'       => 'text',
				'size'       => 'xlarge',
				'input_desc' => __( 'Twitter App comsumer key', 'noise' ),
			),
			array(
				'id'         => 'consumer_secret',
				'label'      => __( 'Consumer Secret', 'noise' ),
				'type'       => 'text',
				'size'       => 'xlarge',
				'input_desc' => __( 'Twitter App comsumer secret', 'noise' ),
			),
			array(
				'id'         => 'access_token',
				'label'      => __( 'Access Token', 'noise' ),
				'type'       => 'text',
				'size'       => 'xlarge',
				'input_desc' => __( 'Twitter App access token', 'noise' ),
			),
			array(
				'id'         => 'access_token_secret',
				'label'      => __( 'Access Token Secret', 'noise' ),
				'type'       => 'text',
				'size'       => 'xlarge',
				'input_desc' => __( 'Twitter App token secret', 'noise' ),
			),
			array(
				'id'    => 'twitter_username',
				'label' => __( 'Twitter Username', 'noise' ),
				'type'  => 'text',
				'size'  => 'large',
			),
			array(
				'id'         => 'num_tweets',
				'label'      => __( 'Tweets to display', 'noise' ),
				'type'       => 'text',
				'size'       => 'mini',
				'default'    => 3,
				'input_desc' => __( 'Enter number of tweets you want to display', 'noise' ),
			),
			array(
				'id'         => 'cache_time',
				'label'      => __( 'Cache Time', 'noise' ),
				'type'       => 'text',
				'size'       => 'mini',
				'default'    => 3600,
				'suffix'     => __( 'second', 'noise' ),
				'input_desc' => __( 'Specify number of seconds you want to cache the tweets', 'noise' ),
			),
		),
	);

	// The latest releases
	$options[] = array(
		'title'  => __( 'Releases', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'releases_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'label'    => __( 'Section Title', 'noise' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'releases_title',
						'type'       => 'text',
						'input_desc' => __( 'Title', 'noise' ),
						'default'    => __( 'Release', 'noise' ),
					),
					array(
						'id'         => 'releases_suffix',
						'type'       => 'text',
						'input_desc' => __( 'Suffix', 'noise' ),
						'default'    => __( 's', 'noise' ),
					),
					array(
						'id'         => 'releases_subtitle',
						'type'       => 'text',
						'input_desc' => __( 'Subtitle', 'noise' ),
						'default'    => __( 'Latest releases', 'noise' ),
					),
				)
			),
			array(
				'id'         => 'releases_num',
				'label'      => __( 'Number Of Albums', 'noise' ),
				'type'       => 'number',
				'default'    => 10,
				'label_desc' => __( 'Number of latest albums to be displayed', 'noise' ),
				'input_desc' => __( 'Enter -1 if you want to dispaly all albums', 'noise' ),
			),
		),
	);

	// Subcribe
	$options[] = array(
		'title'  => __( 'Subscribe', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'subscribe_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'id'    => 'subscribe_bg',
				'label' => __( 'Background Image', 'noise' ),
				'type'  => 'image',
			),
			array(
				'id'         => 'subcribe_shortcode',
				'label'      => __( 'Subscribe Form Shortcode', 'noise' ),
				'type'       => 'text',
				'input_desc' => __( 'Please install a newsletter plugin like <a href="https://wordpress.org/plugins/wysija-newsletters/">MailPoet Newsletters</a> first and enter the shortcode of the subscribe form here. See detailed instruction in the <a href="http://fitwp.github.io/docs/noise">documentation</a>.', 'noise' ),
			),

			array(
				'id'         => 'subscribe_title',
				'label'      => __( 'Title', 'noise' ),
				'type'       => 'text',
				'size'       => 'large',
				'input_desc' => __( 'Title of subscribe section', 'noise' ),
			),
			array(
				'id'         => 'subscribe_desc',
				'label'      => __( 'Description', 'noise' ),
				'type'       => 'textarea',
				'input_desc' => __( 'Enter the description that display bellow the title', 'noise' ),
			),
		),
	);

	// Events
	$options[] = array(
		'title'  => __( 'Events', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'events_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'label'    => __( 'Section Title', 'noise' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'event_title',
						'type'       => 'text',
						'input_desc' => __( 'Title', 'noise' ),
						'default'    => __( 'Event', 'noise' ),
					),
					array(
						'id'         => 'event_suffix',
						'type'       => 'text',
						'input_desc' => __( 'Suffix', 'noise' ),
						'default'    => __( 's', 'noise' ),
					),
					array(
						'id'         => 'event_subtitle',
						'type'       => 'text',
						'input_desc' => __( 'Subtitle', 'noise' ),
						'default'    => __( 'upcoming tours', 'noise' ),
					),
				)
			),
			array(
				'id'         => 'events_num',
				'label'      => __( 'Number Of Events', 'noise' ),
				'type'       => 'number',
				'default'    => 30,
				'label_desc' => __( 'Number of events to be displayed', 'noise' ),
				'input_desc' => __( 'Enter -1 if you want to display all events', 'noise' ),
			),
		),
	);

	// Countdown
	$options[] = array(
		'title'  => __( 'Special Event', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'countdown_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'id'      => 'countdown_bg_type',
				'label'   => __( 'Background Type', 'noise' ),
				'type'    => 'toggle',
				'default' => 'image',
				'options' => array(
					'image' => __( 'Image', 'noise' ),
					'video' => __( 'Video', 'noise' ),
				),
			),
			array(
				'id'    => 'countdown_bg',
				'label' => __( 'Background Image', 'noise' ),
				'type'  => 'image',
			),
			array(
				'label'    => __( 'Background Video', 'noise' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'countdown_video',
						'label'      => __( 'Video URL', 'noise' ),
						'type'       => 'text',
						'size'       => 'xlarge',
						'input_desc' => __( 'Direct video URL and Youtube URL are accepted', 'noise' ),
					),
					array(
						'id'         => 'countdown_video_alt',
						'label'      => __( 'Alternative Video URL', 'noise' ),
						'type'       => 'text',
						'size'       => 'xlarge',
						'input_desc' => __( 'Alternative URL of direct video in other format', 'noise' ),
					),
					array(
						'type'     => 'box',
						'box_type' => 'info',
						'text'     => __( 'To make sure video plays on all browsers, you should enter both URLs to the video with formats <code>mp4 (h.264)</code> and <code>webm</code>', 'noise' ),
					),
				),
			),
			array(
				'id'         => 'counting_event_title',
				'label'      => __( 'Event Title', 'noise' ),
				'type'       => 'text',
				'size'       => 'xxlarge',
				'input_desc' => __( 'Title of countdown section', 'noise' ),
			),
			array(
				'id'         => 'counting_event_desc',
				'label'      => __( 'Event Description', 'noise' ),
				'type'       => 'textarea',
				'input_desc' => __( 'Enter the description that display bellow the title', 'noise' ),
			),
			array(
				'id'         => 'counting_end',
				'label'      => __( 'End time', 'noise' ),
				'type'       => 'text',
				'size'       => 'normal',
				'suffix'     => 'yyyy-mm-dd h:i:s',
				'input_desc' => __( 'Enter the time you want to stop counting', 'noise' ),
			),
		),
	);

	// Artists
	$options[] = array(
		'title'  => __( 'Artists', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'artists_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'label'    => __( 'Section Title', 'noise' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'artist_title',
						'type'       => 'text',
						'input_desc' => __( 'Title', 'noise' ),
						'default'    => __( 'DJ<span class="suffix">s</span> & Band', 'noise' ),
					),
					array(
						'id'         => 'artist_suffix',
						'type'       => 'text',
						'input_desc' => __( 'Suffix', 'noise' ),
					),
					array(
						'id'         => 'artist_subtitle',
						'type'       => 'text',
						'input_desc' => __( 'Subtitle', 'noise' ),
						'default'    => __( 'Artists', 'noise' ),
					),
				)
			),
			array(
				'id'         => 'artists_display',
				'label'      => __( 'Number Of Artists', 'noise' ),
				'type'       => 'radio',
				'label_desc' => __( 'Choose which artists will be displayed', 'noise' ),
				'default'    => 'recent',
				'options'    => array(
					'recent'   => __( 'Most recent artists', 'noise' ),
					'featured' => __( 'Featured Artists', 'noise' ),
				),
			),
			array(
				'id'         => 'artists_num',
				'label'      => __( 'Number Of Artists', 'noise' ),
				'type'       => 'number',
				'default'    => - 1,
				'label_desc' => __( 'Number of artists to be displayed', 'noise' ),
				'input_desc' => __( 'Enter -1 if you want to display all artists', 'noise' ),
			),
		),
	);

	// Blog
	$options[] = array(
		'title'  => __( 'Blog Section', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'blog_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => false,
			),
			array(
				'id'    => 'blog_bg',
				'label' => __( 'Background Image', 'noise' ),
				'type'  => 'image',
			),
			array(
				'id'      => 'blog_section_message',
				'label'   => __( 'Message', 'noise' ),
				'type'    => 'textarea',
				'default' => __( "News<span class='green-dot'>.</span>\nLet's read our fresh news and note", 'noise' ),
			),
			array(
				'id'    => 'blog_url',
				'label' => __( 'Blog URL', 'noise' ),
				'type'  => 'text',
				'size'  => 'xxlarge',
			),
			array(
				'id'      => 'blog_anchor',
				'label'   => __( 'Blog Link Anchor Text', 'noise' ),
				'type'    => 'text',
				'size'    => 'xxlarge',
				'default' => __( 'Go to blog', 'noise' ),
			),
		),
	);

	// Latest News
	$options[] = array(
		'title'  => __( 'Latest News', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'latest_news_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => false,
			),
			array(
				'label'    => __( 'Section Title', 'noise' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'latest_news_title',
						'type'       => 'text',
						'input_desc' => __( 'Title', 'noise' ),
						'default'    => __( 'Post', 'noise' ),
					),
					array(
						'id'         => 'latest_news_suffix',
						'type'       => 'text',
						'default'    => 's',
						'input_desc' => __( 'Suffix', 'noise' ),
					),
					array(
						'id'         => 'alatest_news_subtitle',
						'type'       => 'text',
						'input_desc' => __( 'Subtitle', 'noise' ),
						'default'    => __( 'Latest News', 'noise' ),
					),
				)
			),
			array(
				'id'      => 'latest_news_readmore',
				'label'   => __( 'Read more text', 'noise' ),
				'type'    => 'text',
				'default' => __( 'Read more', 'noise' ),
			),
		),
	);

	// Shop Section
	$options[] = array(
		'title'  => __( 'Shop Section', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'shop_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => false,
			),
			array(
				'id'    => 'shop_bg',
				'label' => __( 'Background Image', 'noise' ),
				'type'  => 'image',
			),
			array(
				'id'      => 'shop_section_title',
				'label'   => __( 'Title', 'noise' ),
				'type'    => 'text',
				'size'    => 'xxlarge',
				'default' => __( 'Shop', 'noise' ),
			),
			array(
				'id'         => 'shop_section_desc',
				'label'      => __( 'Description', 'noise' ),
				'type'       => 'textarea',
				'input_desc' => __( 'Can be written in multiple lines, odd lines will be in larger font size', 'noise' ),
				'default'    => __( "The best albums\nFrom all genres", 'noise' ),
			),
			array(
				'id'    => 'shop_url',
				'label' => __( 'Shop URL', 'noise' ),
				'type'  => 'text',
				'size'  => 'xxlarge',
			),
			array(
				'id'      => 'shop_anchor',
				'label'   => __( 'Shop Link Anchor Text', 'noise' ),
				'type'    => 'text',
				'size'    => 'xxlarge',
				'default' => __( 'View the products', 'noise' ),
			),
		),
	);

	// Latest Products
	$options[] = array(
		'title'  => __( 'Products', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'products_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => false,
			),
			array(
				'label'    => __( 'Section Title', 'noise' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'products_title',
						'type'       => 'text',
						'input_desc' => __( 'Title', 'noise' ),
						'default'    => __( 'Shop', 'noise' ),
					),
					array(
						'id'         => 'products_suffix',
						'type'       => 'text',
						'input_desc' => __( 'Suffix', 'noise' ),
					),
					array(
						'id'         => 'products_subtitle',
						'type'       => 'text',
						'input_desc' => __( 'Subtitle', 'noise' ),
						'default'    => __( 'Recent Products', 'noise' ),
					),
				)
			),
			array(
				'id'      => 'products_show',
				'label'   => __( 'Products to show', 'noise' ),
				'type'    => 'select',
				'default' => 'recent',
				'options' => array(
					'recent'       => __( 'Recent Products', 'noise' ),
					'featured'     => __( 'Featured Products', 'noise' ),
					'best_selling' => __( 'Best Selling Products', 'noise' ),
					'top_rate'     => __( 'Top Rated Products', 'noise' ),
					'sale'         => __( 'Sale Products', 'noise' ),
				),
			),
			array(
				'id'         => 'products_num',
				'label'      => __( 'Number of products', 'noise' ),
				'type'       => 'number',
				'default'    => 20,
				'label_desc' => __( 'Number of latest products to be displayed', 'noise' ),
				'input_desc' => __( 'Enter -1 if you want to display all tracks', 'noise' ),
			),
		),
	);

	// Connect
	$options[] = array(
		'title'  => __( 'Connect', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'connect_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => true,
			),
			array(
				'id'      => 'contact_title',
				'label'   => __( 'Contact Title', 'noise' ),
				'size'    => 'xxlarge',
				'type'    => 'text',
				'default' => __( 'Meet us in person', 'noise' ),
			),
			array(
				'id'      => 'contact_info',
				'label'   => __( 'Contact Information', 'noise' ),
				'type'    => 'textarea',
				'default' => __( 'Enter contact information here', 'noise' ),
			),
			array(
				'id'         => 'contact_email',
				'label'      => __( 'Contact Email', 'noise' ),
				'size'       => 'xxlarge',
				'type'       => 'text',
				'default'    => get_option( 'admin_email' ),
				'input_desc' => __( 'Enter your email to receive emails from visitor', 'noise' ),
			),
			array(
				'id'       => 'google_map',
				'label'    => __( 'Address', 'noise' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'location',
						'type'       => 'text',
						'size'       => 'large',
						'input_desc' => __( 'This address will be used for Google Maps', 'noise' ),
					),
					array(
						'id'         => 'zoom',
						'type'       => 'text',
						'size'       => 'mini',
						'input_desc' => __( 'Zoom Level', 'noise' ),
						'default'    => 15,
					),
				)
			),
			array(
				'id'    => 'social',
				'label' => __( 'Social Media', 'noise' ),
				'type'  => 'social',
			),
		),
	);

	// Custom Content
	$options[] = array(
		'title'  => __( 'Custom Content', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'custom_content_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => false,
			),
			array(
				'label'    => __( 'Section Title', 'noise' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'custom_content_title',
						'type'       => 'text',
						'input_desc' => __( 'Title', 'noise' ),
					),
					array(
						'id'         => 'custom_content_suffix',
						'type'       => 'text',
						'input_desc' => __( 'Suffix', 'noise' ),
					),
					array(
						'id'         => 'custom_content_subtitle',
						'type'       => 'text',
						'input_desc' => __( 'Subtitle', 'noise' ),
					),
				)
			),
			array(
				'id'         => 'custom_content',
				'label'      => __( 'Content', 'noise' ),
				'type'       => 'editor',
				'input_desc' => __( 'Allow <em>Shortcodes</em>', 'noise' ),
			),
		),
	);

	// Custom Content
	$options[] = array(
		'title'  => __( 'Custom Content 2', 'noise' ),
		'level'  => 1,
		'fields' => array(
			array(
				'id'      => 'custom_content_2_enable',
				'label'   => __( 'Enable', 'noise' ),
				'type'    => 'switcher',
				'default' => false,
			),
			array(
				'label'    => __( 'Section Title', 'noise' ),
				'type'     => 'group',
				'children' => array(
					array(
						'id'         => 'custom_content_2_title',
						'type'       => 'text',
						'input_desc' => __( 'Title', 'noise' ),
					),
					array(
						'id'         => 'custom_content_2_suffix',
						'type'       => 'text',
						'input_desc' => __( 'Suffix', 'noise' ),
					),
					array(
						'id'         => 'custom_content_2_subtitle',
						'type'       => 'text',
						'input_desc' => __( 'Subtitle', 'noise' ),
					),
				)
			),
			array(
				'id'         => 'custom_content_2',
				'label'      => __( 'Content', 'noise' ),
				'type'       => 'editor',
				'input_desc' => __( 'Allow <em>Shortcodes</em>', 'noise' ),
			),
		),
	);

	// Blog
	$options[] = array(
		'icon'   => 'news',
		'title'  => __( 'Blog', 'noise' ),
		'fields' => array(
			array(
				'id'         => 'blog_layout',
				'label'      => __( 'Blog Layout', 'noise' ),
				'input_desc' => __( 'Select default layout for blog', 'noise' ),
				'type'       => 'image_toggle',
				'options'    => array(
					'content-sidebar' => THEME_URL . 'inc/functions/options/img/sidebars/single-right.png',
					'sidebar-content' => THEME_URL . 'inc/functions/options/img/sidebars/single-left.png',
				),
				'default'    => 'content-sidebar',
			),
			array(
				'id'         => 'blog_header',
				'label'      => __( 'Blog Header', 'noise' ),
				'input_desc' => __( 'Enable or disable blog header. It\'s the Slider section with different caption that has setting bellow', 'noise' ),
				'type'       => 'switcher',
				'default'    => false,
			),
			array(
				'id'         => 'blog_caption',
				'label'      => __( 'Blog Header Caption/Image', 'noise' ),
				'input_desc' => __( 'Blog header message. If you want to use image for blog header instead of text caption, you can paste link image here ', 'noise' ),
				'type'       => 'text',
				'size'       => 'xxlarge',
				'default'    => __( 'Welcome to our blog', 'noise' ),
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'blog_display',
				'label'      => __( 'Display', 'noise' ),
				'type'       => 'select',
				'options'    => array(
					'excerpt' => __( 'Post excerpt', 'noise' ),
					'content' => __( 'Post content', 'noise' ),
					'more'    => __( 'Post content before more tag', 'noise' ),
				),
				'default'    => 'content',
				'label_desc' => __( 'Select type of post content will be displayed in blog page.', 'noise' ),
			),
			array(
				'id'         => 'blog_content_limit',
				'label'      => __( 'Post Content Limit', 'noise' ),
				'type'       => 'number',
				'suffix'     => __( 'words', 'noise' ),
				'default'    => 55,
				'input_desc' => __( '<strong>Important:</strong> This setting is NOT applied if you select "Post content before more tag" above.', 'noise' ),
			),
			array(
				'id'         => 'readmore',
				'label'      => __( 'Read More', 'noise' ),
				'input_desc' => __( 'Turn on or off read more link', 'noise' ),
				'type'       => 'switcher',
				'default'    => true,
			),
			array(
				'id'         => 'readmore_text',
				'label'      => __( 'Read More Text', 'noise' ),
				'input_desc' => __( 'The anchor text for read more link', 'noise' ),
				'type'       => 'text',
				'default'    => __( 'Read more', 'noise' ),
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'pagination',
				'label'      => __( 'Pagination', 'noise' ),
				'input_desc' => __( 'Select type of pagination', 'noise' ),
				'type'       => 'toggle',
				'default'    => 'ajax',
				'options'    => array(
					'ajax'      => __( 'Ajax Load', 'noise' ),
					'next_prev' => __( 'Next and Prev', 'noise' ),
					'numberic'  => __( 'Numberic Buttons', 'noise' ),
				),
			),
		),
	);

	// shop slider
	$options[] = array(
		'icon'   => 'news',
		'title'  => __( 'Shop', 'noise' ),
		'fields' => array(
			array(
				'id'         => 'shop_header',
				'label'      => __( 'Shop Header', 'noise' ),
				'input_desc' => __( 'Enable or disable shop header. It\'s the Slider section with different caption that has setting bellow', 'noise' ),
				'type'       => 'switcher',
				'default'    => true,
			),
			array(
				'id'         => 'shop_caption',
				'label'      => __( 'Shop Header Caption/Image', 'noise' ),
				'input_desc' => __( 'Shop header message. If you want to use image for shop header instead of text caption, you can paste link image here', 'noise' ),
				'type'       => 'text',
				'size'       => 'xxlarge',
				'default'    => __( 'Welcome to our Shop', 'noise' ),
			),
		),
	);

	// Footer
	$options[] = array(
		'icon'   => 'puzzle-outline',
		'title'  => __( 'Footer', 'noise' ),
		'fields' => array(
			array(
				'id'         => 'enable_footer_colums',
				'label'      => __( 'Footer Columns', 'noise' ),
				'type'       => 'switcher',
				'default'    => false,
				'label_desc' => __( 'Enable on/off footer columns style', 'noise' ),
			),
			array(
				'id'      => 'footer_columns',
				'type'    => 'image_toggle',
				'default' => '4',
				'options' => array(
					'1' => THEME_URL . 'inc/functions/options/img/footer/one-column.png',
					'2' => THEME_URL . 'inc/functions/options/img/footer/two-columns.png',
					'3' => THEME_URL . 'inc/functions/options/img/footer/three-columns.png',
					'4' => THEME_URL . 'inc/functions/options/img/footer/four-columns.png',
				)
			),
			array(
				'id'         => 'footer_logo',
				'label'      => __( 'Footer Logo', 'noise' ),
				'input_desc' => __( 'Logo display in front of copyright', 'noise' ),
				'type'       => 'image',
				'size'       => 'xxlarge',
			),
			array(
				'id'         => 'footer_copyright',
				'label'      => __( 'Footer Copyright', 'noise' ),
				'input_desc' => __( 'HTML and Shortcodes are allowed. Available shortcodes: <code>[year]</code>, <code>[bloginfo]</code>, <code>[site_link]</code>', 'noise' ),
				'type'       => 'text',
				'size'       => 'xxlarge',
			),
			array(
				'type' => 'divider',
			),
			array(
				'id'         => 'footer_scripts',
				'label'      => __( 'Footer Scripts', 'noise' ),
				'type'       => 'textarea',
				'input_desc' => __( 'Enter scripts or code you would like output before <code>&lt;/body&gt;</code>. It can be Google Analytics code or something else.', 'noise' ),
			),
		),
	);

	// Backup
	$options[] = array(
		'icon'   => 'download-outline',
		'title'  => __( 'Backup & Restore', 'noise' ),
		'fields' => array(
			array(
				'id'         => 'backup',
				'label'      => __( 'Backup - Restore', 'noise' ),
				'type'       => 'backup',
				'input_desc' => __( 'You can transfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options" button above', 'noise' ),
			),
		)
	);

	return $options;
}

add_filter( 'fitwp_options_meta', 'noise_options_meta' );

/**
 * Register theme options meta information, like theme links, info, etc.
 *
 * @param array $meta
 *
 * @return array
 * @since  1.0
 */
function noise_options_meta( $meta )
{
	$meta['links'] = array(
		'http://docs.fitwp.com/noise/'    => __( 'Documentation', 'noise' ),
		'http://fitwp.com/envato-support' => __( 'Support', 'noise' ),
	);

	return $meta;
}

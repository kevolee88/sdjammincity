<?php
class Noise_Widget_Tweets extends WP_Widget
{
	/**
	 * Constructor
	 *
	 * @return Noise_Widget_Tweets
	 */
	function __construct()
	{
		parent::__construct(
			'noise-tweets',
			__( 'Noise - Tweets', 'noise' ),
			array(
				'description' => __( 'Display latest tweets', 'noise' ),
			)
		);
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme
	 * @param array $instance An array of settings for this widget instance
	 *
	 * @return void Echoes it's output
	 */
	function widget( $args, $instance )
	{
		$instance = wp_parse_args( $instance, array(
			'title'          => '',
			'tweets_to_show' => '',
		) );

		extract( $args );
		echo $before_widget;

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) )
			echo $before_title . $title . $after_title;
		echo '<div class="tweet-list">';

		$tweets_count = $instance['tweets_to_show'];
		$tweets = $this->get_tweets( $tweets_count );

		foreach ( $tweets as $tweet )
		{
			$time = strtotime( $tweet->created_at );
			$created_time = sprintf( '%s', human_time_diff( $time ) );
			printf( '
				<div class="tweet">
					<p><i class="entypo-twitter"></i>%s</p>
					<time>%s %s</time>
				</div>', 
				$this->convert_links( $tweet->text ),
				$created_time,
				__( 'ago', 'noise' )
			);
		}
		echo '</div>';
		echo $after_widget;
	}

	/**
	 * Replace link tweet
	 *
	 * @param $text
	 *
	 * @return string
	 */
	function convert_links( $text )
	{
		$text = utf8_decode( $text );
		$text = preg_replace( '#https?://[a-z0-9._/-]+#i', '<a rel="nofollow" target="_blank" href="$0">$0</a>', $text );
		$text = preg_replace( '#@([a-z0-9_]+)#i', '@<a rel="nofollow" target="_blank" href="http://twitter.com/$1">$1</a>', $text );
		$text = preg_replace( '# \#([a-z0-9_-]+)#i', ' #<a rel="nofollow" target="_blank" href="http://twitter.com/search?q=%23$1">$1</a>', $text );
		return $text;
	}

	/**
	 * Deals with the settings when they are saved by the admin.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance )
	{
		$instance = array();
		$instance['title']          = strip_tags( $new_instance['title'] );
		$instance['tweets_to_show'] = intval( $new_instance['tweets_to_show'] );
		return $instance;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @param array $instance
	 *
	 * @return array
	 */
	function form( $instance )
	{
		$instance = wp_parse_args( $instance, array(
			'title'          => '',
			'tweets_to_show' => '',
		) );

		$url_admin = admin_url( 'themes.php?page=theme-options#section-twitter-slider' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'noise' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $instance['title'] ?>" style="width:99%;"/>
		</p>
		<p>
			<input  id="<?php echo esc_attr( $this->get_field_id( 'tweets_to_show' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tweets_to_show' ) ); ?>" type="text" value="<?php echo $instance['tweets_to_show'] ?>" size="3"/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'tweets_to_show' ) ); ?>"><?php _e( 'Tweets to show', 'noise' ); ?></label>
		</p>
		<p>
		<?php
			printf( '%s <a target="_blank" href="%s">%s</a>',
			__( 'This widget use settings on', 'noise' ),
			$url_admin,
			 __( 'Theme option', 'noise' )
			);
		?>
		</p>
		<?php
	}

	/**
	* Get latest tweets
	*
	* @param string $tweets_count
	*
	* @return array
	*/
	function get_tweets( $tweets_count )
	{
		$consumer_key        = fitwp_option( 'consumer_key' );
		$consumer_secret     = fitwp_option( 'consumer_secret' );
		$access_token        = fitwp_option( 'access_token' );
		$access_token_secret = fitwp_option( 'access_token_secret' );
		$user_name           = fitwp_option( 'twitter_username' );
		$cache_time          = fitwp_option( 'cache_time' );
		$transient_key       = 'noise_tweets_widget_' . $user_name;
		$tweets_to_show      = $tweets_count ? $tweets_count : fitwp_option( 'num_tweets' );
		$tweets              = get_transient( $transient_key );
		if( !$tweets || !is_array( $tweets ) )
		{
			require THEME_DIR . 'inc/functions/twitter-api-php.php';

			$settings = array(
				'oauth_access_token'        => $access_token,
				'oauth_access_token_secret' => $access_token_secret,
				'consumer_key'              => $consumer_key,
				'consumer_secret'           => $consumer_secret ,
			);

			$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
			$fields = "?screen_name={$user_name}&count={$tweets_to_show}";
			$method = 'GET';

			$twitter = new TwitterAPIExchange( $settings );
			$tweets = $twitter->setGetfield( $fields )->buildOauth( $url, $method )->performRequest();
			$tweets = json_decode( $tweets );

			// Save our new transient.
			set_transient( $transient_key, $tweets, $cache_time  );
		}

		return $tweets;
	}
}

add_action( 'widgets_init', 'noise_register_widget_tweets' );

/**
 * Register widget tweets
 *
 * @return void
 * @since 1.0
 */
function noise_register_widget_tweets()
{
	register_widget( 'Noise_Widget_Tweets' );
}

<?php
$consumer_key        = fitwp_option( 'consumer_key' );
$consumer_secret     = fitwp_option( 'consumer_secret' );
$access_token        = fitwp_option( 'access_token' );
$access_token_secret = fitwp_option( 'access_token_secret' );
$tweets_to_show      = intval( fitwp_option( 'num_tweets' ) );
$user_name           = fitwp_option( 'twitter_username' );
$cache_time          = intval( fitwp_option( 'cache_time' ) );
$transient_key       = 'noise_tweets_' . $user_name;

if ( empty( $user_name ) )
	return;

$tweets = get_transient( $transient_key );
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

if( empty( $tweets ) )
	return;
?>
<section id="section-latest-tweets" class="section-latest-tweets twitter-slider section text-slider slider">
	<div class="parallax"></div>
	<div class="section-mark"></div>
	<div id="tweets-slider" class="quotes-slider lastest-tweet flexslider">
		<div class="cover-icon twitter-cover-icon entypo-twitter"></div>
		<ul class="slides">
			<?php
			foreach ( $tweets as $tweet )
			{
				$time = strtotime( $tweet->created_at );
				$created_time = sprintf( '%s', human_time_diff( $time ) );

				printf(
					'<li><p class="quote">%s</p><span class="created-time">%s %s</span></li>',
					noise_parse_tweet( $tweet->text ),
					$created_time,
					__( 'ago', 'noise' )
				);
			};
			?>
		</ul>
	</div>
</section>
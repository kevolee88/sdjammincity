<?php
/**
 * Get the sort title base on limit character
 *
 * @since  1.0
 *
 * @param  integer $limit
 * @param  string  $suffix
 * @param  boolean $post_id
 * @return string
 */
function noise_title_limit( $limit = 20, $post_id = false, $suffix = "&hellip;" )
{
	$post_id = $post_id ? $post_id : get_the_ID();

	$title = get_the_title( $post_id );

	if ( strlen( $title ) <= $limit )
		return $title;

	$sort_title = mb_substr( $title, 0, $limit );

	if ( ' ' != $title[$limit] && ' ' != $title[$limit - 1] )
	{
		$sort_title = str_replace( strrchr( $sort_title, ' ' ), '', $sort_title );
		$sort_title = trim( $sort_title );
	}

	return $sort_title . $suffix;
}

/**
 * Display limited post content
 *
 * Strips out tags and shortcodes, limits the content to `$num_words` words and appends more link to the end.
 *
 * @param integer $num_words The maximum number of words
 * @param string  $more      More link. Default is "...". Optional.
 * @param bool    $echo      Echo or return output
 *
 * @return string|void Limited content.
 */
function noise_content_limit( $num_words, $more = "&hellip;", $echo = true )
{
	$content = get_the_content();

	// Strip tags and shortcodes so the content truncation count is done correctly
	$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'noise_content_limit_allowed_tags', '<script>,<style>' ) );

	// Remove inline styles / scripts
	$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

	// Truncate $content to $max_char
	$content = wp_trim_words( $content, $num_words );

	if ( $more )
	{
		$output = sprintf(
			'<p>%s <a href="%s" class="more-link" title="%s">%s</a></p>',
			$content,
			get_permalink(),
			sprintf( __( 'Continue reading &quot;%s&quot;', 'noise' ), the_title_attribute( 'echo=0' ) ),
			esc_html( $more )
		);
	}
	else
	{
		$output = sprintf( '<p>%s</p>', $content );
	}

	if ( $echo )
		echo $output;
	else
		return $output;
}

/**
 * Retrieve coordinates for an address
 * Coordinates are cached using transients and a hash of the address
 *
 * @since       1.0
 *
 * @param string $address
 * @param bool   $force_refresh
 *
 * @return void|array
 */
function noise_get_coordinates( $address, $force_refresh = false )
{
	$address_hash = md5( $address );
	$coordinates  = get_transient( $address_hash );

	if ( $force_refresh || $coordinates === false )
	{
		$args     = array( 'address' => urlencode( $address ), 'sensor' => 'false' );
		$url      = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) )
			return;

		$data = wp_remote_retrieve_body( $response );

		if ( is_wp_error( $data ) )
			return;

		if ( $response['response']['code'] == 200 )
		{
			$data = json_decode( $data );

			if ( $data->status === 'OK' )
			{
				$coordinates = $data->results[0]->geometry->location;

				$cache_value['lat']     = $coordinates->lat;
				$cache_value['lng']     = $coordinates->lng;
				$cache_value['address'] = (string) $data->results[0]->formatted_address;

				// cache coordinates for 3 months
				set_transient( $address_hash, $cache_value, 3600 * 24 * 30 * 3 );
				$data = $cache_value;

			}
			elseif ( $data->status === 'ZERO_RESULTS' )
			{
				return __( 'No location found.', 'noise' );
			}
			elseif ( $data->status === 'INVALID_REQUEST' )
			{
				return __( 'Invalid request.', 'noise' );
			}
			else
			{
				return __( 'Something went wrong while retrieving map.', 'noise' );
			}
		}
		else
		{
			return __( 'Unable to contact Google API service.', 'noise' );
		}
	}
	else
	{
		// return cached results
		$data = $coordinates;
	}

	return $data;
}

/**
 * Shortcode Google Map
 *
 * @param string $location
 * @param int    $zoom
 *
 * @since    1.0
 *
 * @return void
 */
function noise_google_map( $location, $zoom = 10 )
{
	$coordinates = noise_get_coordinates( $location );

	if ( !is_array( $coordinates ) )
		return;
	?>
	<div class="noise-map" id="noise-map" data-lat="<?php echo $coordinates['lat'] ?>" data-lng="<?php echo $coordinates['lng'] ?>" data-zoom="<?php echo $zoom ?>"></div>
	<?php
}

/**
 * Replace link tweet
 *
 * @since 1.0
 * @param string $text
 *
 * @return string
 */
function noise_parse_tweet( $text )
{
	$text = preg_replace( '#http://[a-z0-9._/-]+#i', '<a  target="_blank" href="$0">$0</a>', $text );
	$text = preg_replace( '#@([a-z0-9_]+)#i', '@<a  target="_blank" href="http://twitter.com/$1">$1</a>', $text );
	$text = preg_replace( '# \#([a-z0-9_-]+)#i', ' #<a target="_blank" href="http://twitter.com/search?q=%23$1">$1</a>', $text );
	$text = preg_replace( '#https://[a-z0-9._/-]+#i', '<a  target="_blank" href="$0">$0</a>', $text );
	return $text;
}

add_filter( 'excerpt_more', '__return_null' );
add_filter( 'excerpt_length', 'noise_excerpt_length' );

/**
 * Filter function to change except length
 * @param  int $length
 * @return int
 */
function noise_excerpt_length( $length )
{
	$length = intval( fitwp_option( 'blog_content_limit' ) );
	return $length;
}

/**
 * Display numeric pagination
 *
 * @param  object $query
 * @return void
 * @since  1.0
 */
function noise_numeric_pagination( $query = null )
{
	global $wp_query;
	$query = empty( $query ) ? $wp_query : $query;
	?>
	<nav class="pagination paging-navigation numberic-navigation" role="navigation">
		<?php
		$big = 9999;
		$args = array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'total'     => $query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'prev_text' => __( 'Previous', 'noise' ),
			'next_text' => __( 'Next', 'noise' ),
			'type'      => 'plain',
		);

		echo paginate_links( $args );
		?>
	</nav>
	<?php
}

/**
 * Display ajax navigation
 *
 * @param null|object $query
 *
 * @return void
 * @since  1.0
 */
function noise_ajax_pagination( $query = null )
{
	global $wp_query;
	$query = empty( $query ) ? $wp_query : $query;
	?>
	<nav class="pagination paging-navigation ajax-navigation" role="navigation">
		<?php next_posts_link( __( 'Load more', 'noise' ), $query->max_num_pages ); ?>
	</nav>
	<?php
}

/**
 * Display navigation to next/previous pages when applicable
 *
 * @param null|object $query
 *
 * @return void
 * @since  1.0
 */
function noise_simple_pagination( $query = null )
{
	global $wp_query;
	$query = empty( $query ) ? $wp_query : $query;
	?>
	<nav class="pagination paging-navigation simple-navigation" role="navigation">
		<span class="prev-posts"><?php previous_posts_link( __( 'Previous Page', 'noise' ) ); ?></span>
		<span class="next-posts"><?php next_posts_link( __( 'Next Page', 'noise' ), $query->max_num_pages ); ?></span>
	</nav>
	<?php
}

/**
 * Display single navigation
 *
 * @return void
 * @since  1.0
 */
function noise_single_navigation()
{
	if ( is_attachment() )
		return;

	?>
	<nav class="navigation single-navigation" role="navigation">
		<span class="prev-post"><?php previous_post_link( '%link', __( 'Previous Post', 'noise' ) ); ?></span>
		<span class="next-post"><?php next_post_link( '%link', __( 'Next Post', 'noise' ) ); ?></span>
	</nav>
	<?php
}

/**
 * Get post thumbnail base on post format
 *
 * @param  int  $post_id
 * @param  boolean $echo
 * @return string|void
 * @since  1.0
 */
function noise_get_post_thumbnail( $post_id = null, $echo = true )
{
	$post_id = empty( $post_id ) ? get_the_ID() : $post_id;
	$html = '';

	switch( get_post_format( $post_id ) )
	{
		case 'gallery':
			$images = noise_get_meta( 'images', 'type=image_advanced&size=blog-thumbnail', $post_id );
			if ( empty( $images ) )
				break;

			foreach( $images as $image )
			{
				$html .= sprintf( '<li><img src="%s"></li>', $image['url'] );
			}
			$html = sprintf( '<div class="flexslider"><ul class="slides">%s</ul></div>', $html );
			break;

		case 'link':
			$link = get_post_meta( $post_id, 'url', true );
			if ( !empty( $link ) )
				$html = sprintf( '<a href="%s" class="entry-link" rel="nofollow" target="_blank">%s</a>', $link, $link );
			break;

		case 'quote':
			$quote = get_post_meta( $post_id, 'quote', true );
			if ( !empty( $quote ) )
				$html = sprintf(
					'<div class="entry-quote"><p class="quote">%s</p><span class="quote-author">%s</span></div>',
					$quote,
					get_post_meta( $post_id, 'quote_author', true )
				);
			break;

		case 'video':
			$video = noise_get_meta( 'video', 'type=file_input', $post_id );
			if ( empty( $video ) )
				break;

			$ext = wp_check_filetype( $video );

			if ( empty( $ext['ext'] ) )
			{
				$res = parse_url( $video );

				switch ( $res['host'] )
				{
					case 'www.youtube.com':
					case 'youtube.com':
					case 'youtu.be':
					case 'www.vimeo.com':
					case 'vimeo.com':
						$html = wp_oembed_get( $video, array( 'width' => 690 ) );
						break;
				}
			}
			else
			{
				$html = sprintf(
					'<video height="330" width="690" controls="controls" preload="metadata" style="width: %s; height: %s">
						<source type="%s" src="%s" />
					</video>',
					'100%',
					'100%',
					$ext['type'],
					$video
				);
			}
			break;

		case 'audio':
			$audio = noise_get_meta( 'audio', 'type=file_input', $post_id );
			if ( empty( $audio ) )
				break;
			$ext = wp_check_filetype( $audio );
			if ( empty( $ext['ext'] ) )
			{
				$html = wp_oembed_get( $audio, array( 'width' => 690 ) );
			}
			else
			{
				if ( get_post_meta( $post_id, 'spectrum', true ) )
					$html = sprintf( '<canvas class="visualization" id="visualization-%s" width="690" height="100"></canvas>', $post_id );

				$html .= sprintf(
					'<audio id="player-%s" controls="controls" width="690" height="40" style="width: %s" preload="none"><source type="%s" src="%s" /></audio>',
					$post_id,
					'100%',
					$ext['type'],
					$audio
				);
			}
			break;

		default:
			$thumb = noise_get_image( array( 'post_id' => $post_id, 'size' => 'blog-thumbnail', 'echo' => false ) );
			if ( !empty( $thumb ) )
				$html = sprintf( '<a href="%s">%s</a>', get_permalink( $post_id ), $thumb );
			break;
	}

	if ( !empty( $html ) )
		$html = '<div class="entry-thumbnail">' . $html . '</div>';

	if ( !$echo )
		return $html;

	echo $html;
}

/**
 * Display post author box
 *
 * @since  1.0
 * @return void
 */
function noise_get_author_box()
{
	$author_id = get_the_author_meta( 'ID' );
	$author = get_user_by( 'id', $author_id );
	$avatar = get_avatar( $author_id, 110 );
	?>
	<div id="post-author" class="post-author-area">
		<h3 class="about-author-title"><?php _e( 'About Author', 'noise' ) ?></h3>

		<div class="author-box">
			<?php echo $avatar ?>
			<div class="info">
				<span class="num-posts">
					<?php _e( 'Number of posts', 'noise' ) ?>:
					<span><?php echo count_user_posts( $author_id ) ?></span>
				</span>
				<span class="display-name"><?php the_author() ?></span>
				<span class="author-role"><?php echo translate_user_role( $author->roles[0] ) ?></span>
				<p class="author-desc"><?php the_author_meta( 'description' ) ?></p>
			</div>
		</div>
	</div>
	<?php
}

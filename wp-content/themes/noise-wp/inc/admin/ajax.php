<?php
class Noise_Ajax
{
	/**
	 * Setup ajax hooks
	 *
	 * @return Noise_Ajax
	 * @since  1.0
	 */
	function __construct()
	{
		$actions = array(
			'get_extended_player',
			'get_soundcloud_player',
			'get_video_player',
			'get_track_info',
			'get_track_detail',
			'get_images_gallery',
			'update_vote',
			'update_views',
			'send_email',
			'feature_artist',
			'feature_track',
		);
		foreach ( $actions as $action )
		{
			add_action( "wp_ajax_noise_$action", array( $this, $action ) );
			add_action( "wp_ajax_nopriv_noise_$action", array( $this, $action ) );
		}
	}

	/**
	 * Get extended player
	 * @return void
	 * @since  1.0
	 */
	function get_extended_player()
	{
		if ( !isset( $_GET['nonce'] ) || !wp_verify_nonce( $_GET['nonce'], 'get-extended-player' ) )
			wp_die( __( 'Invalid request', 'noise' ) );

		get_template_part( 'sections/extended-player' );
		die;
	}

	/**
	 * Get soundcloud player
	 *
	 * @return void
	 * @since  1.0
	 */
	function get_soundcloud_player()
	{
		get_template_part( 'sections/soundcloud-player' );
		die;
	}

	/**
	 * Get video player
	 *
	 * @return void
	 * @since  1.0
	 */
	function get_video_player()
	{
		get_template_part( 'sections/video-player' );
		die;
	}

	/**
	 * Get video player
	 *
	 * @return void
	 * @since  1.0
	 */
	function get_track_info()
	{
		if ( !empty( $_POST['id'] ) )
		{
			echo noise_get_track_info( $_POST['id'] );
		}

		die;
	}

	/**
	 * Get video player
	 *
	 * @return void
	 * @since  3.2.1
	 */
	function get_track_detail()
	{
		if ( !empty( $_POST['id'] ) )
		{
			echo noise_get_track_detail( $_POST['id'] );
		}

		die;
	}

	/**
	 * Get track gallery
	 *
	 * @return void
	 * @since  1.0
	 */
	function get_images_gallery()
	{
		get_template_part( 'sections/images-gallery' );
		die;
	}

	/**
	 * Update track voting score
	 *
	 * @return void
	 * @since 1.0
	 */
	function update_vote()
	{
		check_ajax_referer( 'vote' . $_POST['id'], 'nonce' );
		$post_id = $_POST['id'];
		$voted = isset( $_COOKIE['_noise_vote_' . $post_id] );

		if ( $voted )
		{
			wp_send_json_error();
			die;
		}

		$votes = array( 'votes', 'votes_week', 'votes_month' );
		foreach( $votes as $vote )
		{
			$score = get_post_meta( $post_id, $vote, true );
			$score += intval( $_POST['score'] );
			update_post_meta( $post_id, $vote, $score );

			$num = get_post_meta( $post_id, $vote . '_num', true );
			$num++;
			update_post_meta( $post_id, $vote . '_num', $num );
		}

		$time = intval( fitwp_option( 'vote_timeout' ) ) * 3600 * 24;
		setcookie( '_noise_vote_' . $post_id, 'yes', time() + $time, COOKIEPATH, COOKIE_DOMAIN, false );

		wp_send_json_success();
		die();
	}

	/**
	 * Update track views
	 *
	 * @return void
	 * @since 1.0
	 */
	function update_views()
	{
		$post_id = $_POST['id'];
		$views = array( 'views', 'views_week', 'views_month' );
		foreach( $views as $view )
		{
			$num = get_post_meta( $post_id, $view, true );
			$num++;
			update_post_meta( $post_id, $view, $num );
		}

		wp_send_json_success();
		die();
	}

	/**
	 * Send contact email
	 *
	 * @return void
	 * @since 1.0
	 */
	function send_email()
	{
		global $_noise_allowed_tags;
		$name    = esc_attr( $_POST['name'] );
		$email   = esc_attr( $_POST['email'] );
		$message = wp_kses( $_POST['message'], $_noise_allowed_tags );
		$to      = fitwp_option( 'contact_email' );

		if ( empty( $to ) )
		{
			wp_send_json_error( 'error' );
			die();
		}

		if ( !is_email( $email ) )
		{
			wp_send_json_error( 'invalid' );
			die();
		}

		$subject = sprintf( __( 'New contact message from %s', 'noise' ), $name );
		$message .= sprintf( '<p>%s: %s</p>', __( 'Reply via her/him email', 'noise' ), $email );

		add_filter( 'wp_mail_content_type', array( $this, 'mail_content_type' ) );
		$mail = wp_mail( $to, $subject, $message );
		remove_filter( 'wp_mail_content_type', array( $this, 'mail_content_type' ) );

		if ( $mail )
			wp_send_json_success();
		else
			wp_send_json_error( 'error' );

		die();
	}

	/**
	 * Set mail content type
	 *
	 * @return string
	 * @since  1.0
	 */
	function mail_content_type()
	{
		return 'text/html';
	}

	/**
	 * Set featured artists
	 *
	 * @since  2.1
	 * @return string
	 */
	function feature_artist()
	{
		$artist_id = $_GET['artist_id'];
		check_ajax_referer( 'noise_feature_artist' . $artist_id );

		$featured = get_post_meta( $artist_id, '_featured_artist', true );
		if ( $featured )
			delete_post_meta( $artist_id, '_featured_artist' );
		else
			update_post_meta( $artist_id, '_featured_artist', '1' );

		// wp_safe_redirect( remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer() ) );
		wp_send_json_success( array( 'featured' => !$featured ) );
		die;
	}

	/**
	 * Set featured artists
	 *
	 * @since  2.2.2
	 * @return string
	 */
	function feature_track()
	{
		$track_id = $_GET['track_id'];
		check_ajax_referer( 'noise_feature_track' . $track_id );

		$featured = get_post_meta( $track_id, '_featured_track', true );
		if ( $featured )
			delete_post_meta( $track_id, '_featured_track' );
		else
			update_post_meta( $track_id, '_featured_track', '1' );

		// wp_safe_redirect( remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer() ) );
		wp_send_json_success( array( 'featured' => !$featured ) );
		die;
	}
}

new Noise_Ajax;

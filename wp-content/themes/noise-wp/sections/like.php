<?php
$social_msg    = noise_kses( fitwp_option( 'social_message' ) );
$follow_user   = esc_attr( fitwp_option( 'follow_user' ) );
$follow_text   = fitwp_option( 'twitter_follow_text' );
$facebook_url  = esc_attr( fitwp_option( 'facebook_page' ) );
$facebook_text = fitwp_option( 'facebook_like_text' );
?>

<section id="section-like" class="section-like section">
	<div class="parallax"></div>
	<div class="section-mark"></div>
	<div class="inner">
		<div class="social-message">
		<?php
		$messages = explode( "\n", $social_msg );
		foreach ( $messages as $index => $msg )
		{
			printf( '<p class="%s">%s</p>', $index % 2 ? 'even' : 'odd', $msg );
		}
		?>
		</div>
		<div class="social">
			<?php if ( !empty( $facebook_url ) ) : ?>
				<span class="facebook-like social-button-large" data-link="<?php echo esc_attr( HOME_URL ); ?>">
					<a href="<?php echo $facebook_url ?>" target="_blank"><?php echo $facebook_text; ?></a>
				</span>
			<?php endif; ?>

			<?php if( !empty( $facebook_url ) && !empty( $follow_user ) ) : ?>
				<span class="middle-area"><?php _e( 'or', 'noise' );?></span>
			<?php endif; ?>

			<?php if ( !empty( $follow_user ) ) : ?>
				<span class="twitter-follow social-button-large">
					<a href="https://twitter.com/intent/follow?screen_name=<?php echo $follow_user; ?>" target="_blank">
						<?php echo $follow_text; ?>
					</a>
				</span>
			<?php endif; ?>
		</div>
	</div>
</section>

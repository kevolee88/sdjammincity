<?php
$contact_title = noise_kses( fitwp_option( 'contact_title' ) );
$contact_info  = noise_kses( fitwp_option( 'contact_info' ) );
$socials       = fitwp_option( 'social' );
$socials       = $socials ? array_filter( $socials ) : array();
$location      = esc_attr( fitwp_option( 'location' ) );

if ( empty( $location ) )
	return;
?>
<section id="section-connect" class="section-connect section">
	<?php noise_google_map( $location, intval( fitwp_option( 'zoom' ) ) ); ?>

	<div class="inner social">
		<?php
		foreach ( $socials as $social => $name )
		{
			printf( '<a href="%s" target="_blank"><span class="entypo-%s icon-social"></span><span class="title">%s</span></a>',
				esc_url( $name ),
				( $social == 'google' ) ? 'googleplus' : ( $social == 'reverbnation' ? 'star' : $social ),
				( $social == 'google' ) ? 'Google Plus' : $social
			);
		}
		?>
	</div>

	<div class="contact-form">
		<div class="overlay"></div>
		<div class="inner">
			<div class="contact-info box col6">
				<h3><?php echo $contact_title; ?></h3>
				<div >
					<?php echo wpautop( $contact_info ); ?>
				</div>
			</div>
			<div class="form box col6">
				<h3><?php _e( 'Drop us a line', 'noise' ) ?></h3>
				<input type="text" name="name" placeholder="<?php _e( 'Your name', 'noise' ) ?>">
				<input type="email" name="email" placeholder="<?php _e( 'Your mail address', 'noise' ) ?>">
				<textarea name="message" placeholder="<?php _e( 'Your message', 'noise' ) ?>"></textarea>
				<input type="button" value="<?php _e( 'Submit', 'noise' ) ?>" class="send-mail">
				<div class="notices">
					<div class="processing"><?php _e( 'Processing...', 'noise' ) ?></div>
					<div class="miss"><?php _e( 'You must fill all fields', 'noise' ) ?></div>
					<div class="invalid"><?php _e( 'You must enter correct email', 'noise' ) ?></div>
					<div class="error"><?php _e( 'We have error while sending your message', 'noise' ) ?></div>
					<div class="success"><?php _e( 'Your message has been sent', 'noise' ) ?></div>
				</div>
			</div>
		</div>
	</div>
	<div id="toggle-contact-form" class="entypo-mail toggle-contact-form"></div>
</section>
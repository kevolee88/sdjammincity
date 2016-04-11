<?php
$title     = noise_kses( fitwp_option( 'subscribe_title' ) );
$desc      = noise_kses( fitwp_option( 'subscribe_desc' ) );
$shortcode = noise_kses( fitwp_option( 'subcribe_shortcode' ) );

if ( empty( $shortcode ) )
	return;
?>
<section id="section-subscribe" class="section-subscribe section">
	<div class="parallax"></div>
	<div class="section-mark"></div>
	<div class="inner">
		<div class="col8 subcribe">
			<div class="subscribe-title"><?php echo $title; ?></div>
			<div class="subscribe-desc"><?php echo $desc; ?></div>
			<div class="subcribe-form">
				<?php echo do_shortcode( $shortcode ); ?>
			</div>
		</div>
	</div>
</section>

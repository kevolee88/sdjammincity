<?php get_header(); ?>

<div class="not-found">
	<div class="parallax"></div>
	<div class="section-mark"></div>
	<div class="inner">
		<p class="heading-404">404 <span>0<span>ops!</span></span></p>
		<p class="desc-404"><?php _e( 'The page your\'re looking for not found', 'noise' ) ?></p>
		<a class="return-home" href="<?php echo esc_url( HOME_URL ); ?>"><?php _e( 'Return to homepage', 'noise' ) ?></a>
	</div>
</div>

<?php get_footer(); ?>
<?php
$testimonials = fitwp_option( 'testimonials' );

if ( empty( $testimonials ) )
	return;

?>
<section id="section-testimonials" class="section-testimonials text-slider slider section">
	<div class="parallax"></div>
	<div class="section-mark"></div>
	<div id="quotes-slider" class="flexslider testimonial-slider quotes-slider">
		<div class="cover-icon">&ldquo;</div>
		<ul class="slides">
			<?php
			foreach ( $testimonials as $testimonial )
			{
				if ( !empty( $testimonial['content'] ) )
				{
					printf( '<li><p class="quote">%s</p><span class="quote-author">%s</span></li>',
						noise_kses( $testimonial['content'] ),
						noise_kses( $testimonial['author'] )
					);
				}
			}
			?>
		</ul>
	</div>
</section>

<?php
$title    = noise_kses( fitwp_option( 'shop_section_title' ) );
$messages = noise_kses( fitwp_option( 'shop_section_desc' ) );
$messages = explode( "\n", $messages );
$link     = fitwp_option( 'shop_url' );
$anchor   = fitwp_option( 'shop_anchor' );
?>

<section id="section-shop" class="section-shop section">
	<div class="parallax"></div>
	<div class="section-mark"></div>
	<div class="inner">
		<div class="shop-title"><?php echo $title ?></div>
		<div class="shop-message">
			<?php
			foreach ( $messages as $index => $msg )
			{
				printf( '<p class="%s">%s</p>', $index % 2 ? 'even' : 'odd', $msg );
			}
			?>
		</div>

		<div class="shop-link blog-link">
			<a href="<?php echo $link ?>"><?php echo $anchor ?></a>
		</div>
	</div>
</section>

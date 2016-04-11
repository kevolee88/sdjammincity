<?php
$id = $_POST['id'];
$images = noise_get_meta( 'images', 'type=image_advanced&size=lightbox', $id );

if ( empty( $images ) )
	return;
?>

<div id="images-gallery-slideshow" class="flexslider images-gallery-slideshow">
	<ul class="slides">
		<?php
		foreach ( $images as $image )
		{
			printf( '<li><img src="%s"></li>', $image['url'] );
		}
		?>
	</ul>
</div>
<div class="images-gallery-bottom">
	<div class="images-gallery-paginate">
		<span class="current-slide">01</span> / <span class="total-slides"></span>
	</div>
	<div class="post-share social-likes" data-url="<?php echo esc_attr( get_permalink( $id ) ); ?>" data-title="<?php echo esc_attr( get_the_title( $id ) ); ?>" data-zeroes="yes">
		<div class="facebook"></div>
		<div class="twitter"></div>
		<div class="plusone"></div>
	</div>
</div>

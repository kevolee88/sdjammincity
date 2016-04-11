<?php
$post_id = intval( $_GET['id'] );

$file = get_post_meta( $post_id, 'upload', true );

if ( empty( $file ) )
	return;

$ext = wp_check_filetype( $file );

$artist = noise_get_meta( 'artist', '', $post_id );
$artist_name = $artist ? get_the_title( $artist ) : '';
$image = noise_get_meta( 'cover', 'type=image_advanced', $post_id );

if ( empty( $image ) )
{
	$image = noise_get_image( array(
		'post_id'   => $post_id,
		'size'      => 'full',
		'format'    => 'src',
		'echo'      => false,
		'thumbnail' => false,
		'meta_key'  => 'images',
		'default'   => 'http://placehold.it/1170x540',
	) );
}
else
{
	$image = array_shift( $image );
	$image = $image['full_url'];
}
?>

<div class="track-visual">
	<div class="image" style="background-image: url(<?php echo $image ?>)">
		<div class="caption">
			<a href="#" class="text" data-id="<?php echo $_GET['id'] ?>">
				<span class="big"><?php echo esc_html( get_the_title( $post_id ) ); ?></span>
				<span class="small"><?php echo esc_html( $artist_name ); ?></span>
			</a>
		</div>
		<canvas width="1150" height="120" class="visualization" id="visualization"></canvas>
	</div>
	<div class="details"></div>
</div>

<audio id="extended-player" controls="controls" width="100%" preload="auto" autoplay>
	<source type="<?php echo esc_attr( $ext['type'] ); ?>" src="<?php echo esc_url( $file ); ?>" />
</audio>
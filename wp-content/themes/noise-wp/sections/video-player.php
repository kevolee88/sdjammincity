<?php
if ( !isset( $_POST['id'] ) || empty( $_POST['id'] ) )
{
	printf( '<p class="message">%s</p>', __( 'No video available', 'noise' ) );
	return;
}

$id = $_POST['id'];
$videos = noise_get_meta( 'videos', 'type=file_input', $id );

if ( empty( $videos ) )
{
	printf( '<p class="message">%s</p>', __( 'No video available', 'noise' ) );
	return;
}

echo '<div class="videos-slideshow">';
echo '<ul class="slides">';

foreach( $videos as $video )
{
	$ext = wp_check_filetype( $video );

	if ( empty( $ext['ext'] ) )
	{
		$html = wp_oembed_get( $video, array( 'width' => 1150, 'height' => 580 ) );
	}
	else
	{
		$html = sprintf(
			'<video height="580" width="1150" controls="controls" preload="metadata">
				<source type="%s" src="%s" />
			</video>',
			$ext['type'],
			esc_url( $video )
		);
	}

	printf( '<li>%s</li>', $html );
}

echo '</ul>';
echo '</div>';
<?php
if ( !isset( $_POST['url'] ) || empty( $_POST['url'] ) )
{
	echo '<p class="message">' . __( 'This track dose not has SoundCloud version', 'noise' ) . '</p>';
	return;
}

$player = wp_oembed_get( $_POST['url'], array( 'width' => 1150, 'height' => 170 ) );
echo $player;
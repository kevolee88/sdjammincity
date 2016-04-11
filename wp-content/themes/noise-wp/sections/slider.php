<?php
$sliders = fitwp_option( 'top_slider' );
$sliders = $sliders ? array_filter( $sliders ) : array();
$type    = fitwp_option( 'slider_type' );
$content_type = fitwp_option( 'content_type' );
$slider_content = trim( fitwp_option( 'slider_content' ) );
$caption = fitwp_option( 'blog_caption' );
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && ( is_woocommerce() || is_cart() || is_checkout() ) )
{
	$caption = fitwp_option( 'shop_caption' );
}
?>
<section id="section-slider" class="section-slider section <?php echo $type; ?>">
	<?php
	switch ( $type ) {
		case 'images':
			echo '<div id="background-slider" class="background-slider"><ul class="slides">';
			foreach( $sliders as $slider )
			{
				if ( !empty( $slider['caption'] ) && !empty( $slider['image'] ) )
				{
					echo '<li style="background-image: url(' . esc_attr( $slider['image'] ) . ')"></li>';
				}
			}
			echo '</ul></div>';
			break;

		case 'video':
			$detect = new Mobile_Detect;
			if ( $detect->isMobile() )
			{
				echo '<div class="parallax"></div>';
			}
			else
			{
				$video = fitwp_option( 'slider_video' );
				$ext = wp_check_filetype( $video );
				if ( empty( $ext['ext'] ) )
				{
					parse_str( parse_url( $video, PHP_URL_QUERY ), $video_args );
					echo '<div id="top-youtube-bg" data-video="' . $video_args['v'] . '"></div>';
				}
				else
				{
					$video_alt      = fitwp_option( 'slider_video_alt' );
					$video_alt_type = wp_check_filetype( $video_alt );
					$alt            = !empty( $video_alt_type['ext'] ) ? sprintf( '<source type="%s" src="%s" />', $video_alt_type['type'], $video_alt ) : '';
					printf(
						'<div class="parallax"><video id="video-background" preload="auto" autoplay loop muted><source type="%s" src="%s" />%s</video></div>',
						$ext['type'],
						$video,
						$alt
					);
				}
			}
			break;

		default:
			echo '<div class="parallax"></div>';
			break;
	}
	?>

	<div class="section-mark"></div>
	<div class="section-content section-image">

		<?php if ( is_page_template( 'template-onepage.php' ) ) : ?>
			<?php if ( $content_type == 'content_text' ) : ?>
				<div class="flexslider">
					<ul class="slides">
					<?php
					foreach( $sliders as $slider )
					{
						if ( !empty( $slider['caption'] ) )
						{
					      	echo '<li><div class="caption">' . noise_kses( $slider['caption'] ) . '</div></li>';
						}
					}
					?>
					</ul>
				</div>
			<?php else : ?>
			<?php
				if ( !empty( $slider_content ) )
				{
			      	echo '<div class="caption-holder"><div class="caption">' .  do_shortcode( wpautop( $slider_content ) ) . '</div></div>';
				}
			?>
			<?php endif; ?>
		<?php elseif ( $caption ) : ?>
			<?php $url = filter_var( $caption, FILTER_VALIDATE_URL );?>
				<div class="caption-holder <?php if ( $url ) echo 'blog-caption-image'; else echo 'blog-caption';?>">
					<div class="caption <?php if ( $url ) echo 'blog-image';?>">
					<?php
					if ( $url )
					{
						printf( '<img src="%s">', noise_kses( $caption ) );
					}
					else
					{
						echo noise_kses( $caption );
					}
					?>
					</div>
				</div>
		<?php endif; ?>
		<?php
			$class_bg_sound = 'class="entypo-mute"';
			if( fitwp_option( 'auto_play_sound_video' ) )
				$class_bg_sound = 'class="entypo-sound"';
			if ( 'video' == $type ) echo '<a id="toggle-bg-sound" ' . $class_bg_sound . '></a>';
		?>
	</div>
</section>

<?php
$title   = fitwp_option( 'custom_content_2_title' );
$content = trim( fitwp_option( 'custom_content_2' ) );
?>
<section id="section-custom-content-2" class="section-custom-content-2 section">
	<div class="inner">
		<?php if( !empty( $title ) ) : ?>
			<h2 class="section-title">
				<?php
				$suffix = noise_kses( fitwp_option( 'custom_content_2_suffix' ) );
				$desc   = noise_kses( fitwp_option( 'custom_content_2_subtitle' ) );
				printf(
					'<span>%s</span>%s%s',
					noise_kses( $title ),
					$suffix ? '<span class="suffix">' . $suffix . '</span>' : '',
					$desc ? '<span class="desc">' . $desc . '</span>' : ''
				);
				?>
			</h2>
		<?php endif; ?>

		<div class="custom-content">
			<?php echo do_shortcode( wpautop( $content ) ) ?>
		</div>
	</div>
</section>
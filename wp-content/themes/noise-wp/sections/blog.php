<?php
$messages = noise_kses( fitwp_option( 'blog_section_message' ) );
$messages = explode( "\n", $messages );
$link     = fitwp_option( 'blog_url' );
$anchor   = fitwp_option( 'blog_anchor' );
?>

<section id="section-blog" class="section-blog section">
	<div class="parallax"></div>
	<div class="section-mark"></div>
	<div class="inner">
		<div class="blog-message social-message">
		<?php
		foreach ( $messages as $index => $msg )
		{
			printf( '<p class="%s">%s</p>', $index % 2 ? 'even' : 'odd', $msg );
		}
		?>
		</div>

		<div class="blog-link">
			<a href="<?php echo $link ?>"><?php echo $anchor ?></a>
		</div>
	</div>
</section>
<?php if ( 'full-content' != fitwp_option( 'blog_layout' ) ) : ?>
	<aside id="primary-sidebar" class="primary-sidebar sidebar">
		<?php if ( !dynamic_sidebar( 'blog-sidebar' ) ) : ?>

			<div class="widget widget_text">
				<h4 class="widget-title"><?php _e( 'Primary Sidebar', 'noise' ); ?></h4>

				<div class="textwidget">
					<p>
						<?php
						printf( __( 'This is the blog sidebar (widget area). Please go to <a href="%s">Appearance &rarr; Widgets</a> to add content to this area', 'noise' ), admin_url( 'widgets.php' ) );
						?>
					</p>
				</div>

			</div>

		<?php endif; ?>
	</aside>
	<div class="minimize-sidebar"></div>
<?php endif; ?>

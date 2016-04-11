		</div><!-- end #main -->

		<footer id="footer" class="footer">
			<div class="inner">
				<?php
				$footer_columns = fitwp_option( 'footer_columns' );
				$class          = 'col' . ( 12 / $footer_columns );
				?>
				<?php if ( fitwp_option( 'enable_footer_colums' ) ): ?>
					<?php for ( $i = 1; $i <= $footer_columns; $i ++ ): ?>
						<div id="footer-sidebar-<?php echo $i ?>" class="footer-sidebar <?php echo $class; ?>">
							<?php
							if ( ! dynamic_sidebar( "footer-$i" ) )
								printf( __( 'This is the Footer Sidebar %s (widget area). Please go to <a href="%s">Appearance &rarr; Widgets</a> to add widgets to this area', 'glamo' ), $i, admin_url( 'widgets.php' ) );
							?>
						</div>
					<?php endfor; ?>
				<?php endif; ?>
				<?php
				if ( $copyright = fitwp_option( 'footer_copyright' ) )
				{
					$logo = fitwp_option( 'footer_logo' );
					printf(
						'<div class="copyright col10">%s%s</div>',
						$logo ? '<img src="' . $logo . '" class="footer-logo">' : '',
						do_shortcode( $copyright )
					);
				}
				else
				{
					printf( '<div class="copyright default-copyright col3 left">%s<span class="green-dot">.</span><span class="copyright-info">%s</span></div>', __( 'Noise', 'noise' ), __( '&copy; 2014', 'noise' ) );
				}
				?>
				<a id="scroll-top" href="#top" class="scroll-top right entypo-arrow-up7"></a>
			</div>
		</footer>

	</div><!-- end #wrapper -->

<?php wp_footer(); ?>
</body>
</html>

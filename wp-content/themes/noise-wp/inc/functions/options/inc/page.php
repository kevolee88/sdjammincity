<div class="wrap">

	<form method="POST" action="" id="theme-options" class="clearfix">
		<div id="menuback"></div>
		<ul id="menu">
			<?php
			$tpl = '<li><a href="#%s"><i class="menu-icon icon-%s"></i> %s</a>';
			$level = 0;
			$html = array();
			foreach ( FitWP_Options::$sections as $section )
			{
				// Open submenu
				if ( $section['level'] > $level )
				{
					$level++;
					$html[] = '<ul class="menu">';
				}

				// Close submenu
				elseif ( $section['level'] < $level )
				{
					$level--;
					$html[] = '</ul></li>';
				}

				// Normal close item
				else
				{
					$html[] = '</li>';
				}

				// Item
				$html[] = sprintf( $tpl, 'section-' . sanitize_title( $section['title'] ), $section['icon'], $section['title'] );
			}
			$html[] = '</li>';
			while ( $level )
			{
				$level--;
				$html[] = '</ul></li>';
			}
			array_shift( $html );
			echo implode( '', $html );
			?>
		</ul>

		<div id="content">
			<div class="toolbar top">
				<button type="button" class="button reset-options"><?php echo __( 'Reset Options', 'fitwp' ) ?></button>
				<button type="button" class="button save-options"><?php echo __( 'Save Changes', 'fitwp' ) ?></button>
				<span class="ajax-processing"></span>

				<div class="options-header">
					<?php $theme = wp_get_theme(); ?>
					<h1><?php echo $theme->name; ?></h1>
					<span class="version"><?php echo $theme->version; ?></span><br>
					<span class="links">
						<?php
						printf(
							__( 'By <a href="%s" target="_blank">%s</a>', 'fitwp' ),
							$theme->{'Author URI'},
							$theme->{'Author Name'}
						);
						$meta = apply_filters( 'fitwp_options_meta', array() );
						if ( !empty( $meta['links'] ) )
						{
							foreach ( $meta['links'] as $link => $text )
							{
								printf( ' | <a href="%s" target="_blank">%s</a>', $link, $text );
							}
						}
						?>
					</span>
				</div>
			</div>

			<?php
			// Display section fields
			$obj = new FitWP_Options_Fields;
			foreach ( FitWP_Options::$sections as $section )
			{
				if ( empty( $section['fields'] ) )
					continue;

				echo '<div class="section" id="section-' . sanitize_title( $section['title'] ) . '">';
				echo $obj->show( $section['fields'] );
				echo '</div>';
			}
			?>

			<div class="toolbar bottom">
				<button type="button" class="button reset-options"><?php echo __( 'Reset Options', 'fitwp' ) ?></button>
				<button type="button" class="button save-options"><?php echo __( 'Save Changes', 'fitwp' ) ?></button>
				<span class="ajax-processing"></span>
			</div>
		</div>
	</form>
</div>

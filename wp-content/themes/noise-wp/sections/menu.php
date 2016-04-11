<header id="nav-menu" class="nav-menu">
	<div class="inner clearfix">
		<div class="logo left"><h1><a href="<?php echo esc_url( HOME_URL ); ?>"><?php bloginfo( 'name' ); ?></a></h1></div>
		<nav id="nav"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => '' ) ) ?></nav>
		<?php if ( is_page_template( 'template-onepage.php' ) ) : ?><div id="player-icon" class="player-icon entypo-music"></div><?php endif; ?>
	</div>
</header>
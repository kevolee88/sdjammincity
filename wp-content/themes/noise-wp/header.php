<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<!--[if lt IE 9]>
	<script src="//cdn.jsdelivr.net/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="//cdn.jsdelivr.net/respond/1.3.0/respond.min.js"></script>
	<![endif]-->

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="load-screen"></div>
	<div id="wrapper">

		<div id="main" class="main clearfix">
			<?php
			if ( fitwp_option( 'opener_enable' ) && !isset( $_COOKIE['unlock'] ) )
				get_template_part( "sections/opener" );
<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'sdjammincity');

/** MySQL database username */
define('DB_USER', 'sdjammincity');

/** MySQL database password */
define('DB_PASSWORD', 'h6VGjGMZPJEKs8Bb');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'lm %Kf_<:{A4i+vA+:w5fl7J?ijmpVn],vQf1sB4RbyA/_xJ6:5R~k%p(=xtU71)');
define('SECURE_AUTH_KEY',  'a5b?sF@C)e}+nmqQ@!#=gNFsfH[lw%7dNL0_V`k_;+vBf-Zt}OfZ)> )naCG-u3V');
define('LOGGED_IN_KEY',    'i!ez+2=laqK0X5W^|+iSPz4o](`,:,a&NnHYQ8xZ_,;w7[7l1GtD4^d 9>h&+zod');
define('NONCE_KEY',        'aWTP*Hqw7+B-I:c$)GbUfg>#Gg?GHXQ3%7`wNMF7m@H;?zkH{u)3H**pK|@Zn-,v');
define('AUTH_SALT',        'C6oo.vrT&II3h?Qi! #.%I?Ew,Y0;+V+C[<UG!M-B46~{{j. Hh1h,++;2/HGwTj');
define('SECURE_AUTH_SALT', 'H0L#QVThqQvTH&wyRca5)NN].HZEqCq-1_d+a<&(%@E^lTi_35Fq#ys9EAwHHgh9');
define('LOGGED_IN_SALT',   '(^_:cR7)#|DPU@/-B-d7M(C7oPLOcg_|>.x|[+^;U#{Jcq8fn|A>au8=x>ku.|[>');
define('NONCE_SALT',       '>|=3M!s;mECHKEqU=oc&F9x[0=@!EA%-|TU|*;CWmJbllU%g&I,&?#dZ.W1g0w,T');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

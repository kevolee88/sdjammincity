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
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '.wg=|HYx7$--B$<S{oSi+{W3/H_jRFj}7tO0|37;tNa{l&5F^V7H4<u&&}SFCf,|');
define('SECURE_AUTH_KEY',  '{+m2v=F>&(&F-k2;@DL<g{Ldbz.M:t|cs5{,97O* -W-J;eMn=L1uboFKu{*.vkp');
define('LOGGED_IN_KEY',    'MB^9x(^2<ZhbN X|z;{ZD*&+?!yFs+,<ZV^5{Y+BEgMZP5HKM]Y4HhD0=c0D#!!w');
define('NONCE_KEY',        'bCM^[op7-8`^y(If&do8R):@e@&:Q-4IYKMU|dZnyqd>LR02b_P6!n)jX4T^R||5');
define('AUTH_SALT',        '=.{}?e_c[XG3Vl:8SU?+~tyC0ko-+EaOp~/[|DwSH&)uN%}~{4)+2>EZM]_+W4jb');
define('SECURE_AUTH_SALT', 'va}wQs8:TY&M+qe[7t3wUD<4DNjP!C#`lLC+.V}}+<C|]RCwMCmi|r/Rw9Gk_?yC');
define('LOGGED_IN_SALT',   '07_ZCbT3-6gKeFD+%}D|=;T%`GJ.kHo,C4</pAv|d+rhZ-tBi Q0B:M+;FG^74*;');
define('NONCE_SALT',       'L:&2!cT-,dC[k9f>B3iOTMf.M E|):f|YL]?3+%dV^Pe/.8jcJ}? =x4<1c|6,{>');

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

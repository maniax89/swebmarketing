<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db511792943');

/** MySQL database username */
define('DB_USER', 'dbo511792943');

/** MySQL database password */
define('DB_PASSWORD', 'sweb2014');

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
define('AUTH_KEY',         'v XH1Bw:J,M,}K=wLYUAb-_+S!94TAV1d@^B?HCfvBYol;+Tsvc=6^FNb3Sf$;|m');
define('SECURE_AUTH_KEY',  '}0[^r6(Y+nobN`/EC1`STSy5v7<X_nmnGBX*)yBwyOnWj{G%9`eg_~>6Qw.a>|tO');
define('LOGGED_IN_KEY',    '4 YVm`u2a$G>@lRvBYGSw=--B3g%JBels bj3b$F/b//7Q_ms-]a>pufpCq<2d?j');
define('NONCE_KEY',        '0bH{ugmAAomYU=1s-Q}&TmlW%xr-=]<tk%>E9sV3I;FD+;9p5RA{,*G[>j6`TCC`');
define('AUTH_SALT',        'b*72#2-&6FEE5H nkEjRoCJ06Q<-a5Rx|wK.Fa#gfXRs~w4nKNy=v`_28-x-*=UM');
define('SECURE_AUTH_SALT', 'QHr^LVX0]4? 63%4GY9<-JUNksI^tTgG3>MXnd9D1SW0PlCn;/B(,o|KE0&qL35E');
define('LOGGED_IN_SALT',   'dsO?r-f.O3VKHTHxys{/-A/g)iY^m}u+0+i mv+ZeBxfu 8 ?4yh 2w~@Sjpu#}U');
define('NONCE_SALT',       '`}XmEz`>*Tm{$4v~]<V>IPG}d(Ga+?tuorBpb&dj4]_`@YBiO )z/ynsbyC:cqvb');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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
<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

$is_dev = false;

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'WP_DB_NAME');

/** MySQL database username */
define( 'DB_USER', 'WP_DB_USER');

/** MySQL database password */
define( 'DB_PASSWORD', 'WP_DB_PASSWORD');

/** MySQL hostname */
define( 'DB_HOST', 'WP_DB_HOST');

if(!$is_dev):
    define( 'DB_HOST', '193.203.175.55:3306');
    define( 'DB_PASSWORD', 'bj^OSAy9#');
    define( 'DB_USER', 'u919907044_testf7p');
    define( 'DB_NAME', 'u919907044_testf7p');
endif;

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define( 'DISALLOW_FILE_EDIT', true );

define( 'AS3CF_SETTINGS', serialize( array(
    'provider' => 'aws',
    'access-key-id' => 'AWS_S3_ACCESS_KEY',
    'secret-access-key' => 'AWS_S3_SECRET_KEY',
	'bucket' => 'WP_S3_BUCKET'
) ) );

if(!$is_dev):
    define( 'FORCE_SSL', true );
    define( 'FORCE_SSL_ADMIN',true );
    $_SERVER['HTTPS']='on';
endif;

define('WP_ALLOW_MULTISITE', true);

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', $_SERVER['HTTP_HOST']);
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);


define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST']);
define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST']);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '1W[T/!lx+!N>n+U!=T^]%N37vZKdXY7I*i^uxG)#%`R,bn;(sn<jz6!8zYGz*kkR' );
define( 'SECURE_AUTH_KEY',  '9TkeZ;0;W k6~38eZ}M0Yz5rr`e)A=)f%;q4?H<:@7ajJ7m9jAFTGrT?<O3[3zpj' );
define( 'LOGGED_IN_KEY',    'L5$up1dxDc^qC`yWdoUUC34:Sn;-Th!xBH[UbqG2ZGwF{Hkae#gB-dz! [totI8-' );
define( 'NONCE_KEY',        'a;,>D?(Gv$GwJ;k!u1c!]Eh@J8^V_Q.WT?3:@:w?&4#TIM)c&2l,$w2:UJBbk%B%' );
define( 'AUTH_SALT',        'R.r+0I%o+]r{`#iS)8,5ZW#8TPzi#7kV933w!=(bQz-.`m)EUS>7O.#uT{2Xg)Kl' );
define( 'SECURE_AUTH_SALT', 'YWRq<if63(.#IgFpZqu}eUWFO3w2#G;N4T)Z>IJZ_xb/pw:TCTV?[=31-:dC;[Zj' );
define( 'LOGGED_IN_SALT',   '~3SO@UEx%Y111%]!K>>vBOg]v##t8XgeCsNW{)$2^!eFS&dmXE?Mr$^nUh-*Fbjv' );
define( 'NONCE_SALT',       '`IxT@]zkO!A@O1l6R@OeZ/:0@bn)4lUL3B[_6byZ5+adLw?qsqz!(vl_2Sz)0=po' );


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

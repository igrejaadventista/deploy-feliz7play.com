<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', $_ENV['WP_DB_NAME']);

/** MySQL database username */
define( 'DB_USER', $_ENV['WP_DB_USER']);

/** MySQL database password */
define( 'DB_PASSWORD', $_ENV['WP_DB_PASSWORD']);

/** MySQL hostname */
define( 'DB_HOST', $_ENV['WP_DB_HOST'] . ':3306');

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'pO9T7>_}LVnQ~BJGOHskFD/Ch(glf5h%N8%bg=5u_;bJ:107F%jsPl~XK<,[^J~l' );
define( 'SECURE_AUTH_KEY',   'DS7o;7?N7/dgu[XM!OSCr1a`qbF)H8Eo:(h0b8cS3 6e#l9oCklRqL3ym]@*F4zE' );
define( 'LOGGED_IN_KEY',     'kTuuGA>#,1cAF|sY21j3Xc7hX6{Z5VS8p.*=>(lUNu8#H4cDd#xyxDYHvP&z&BEv' );
define( 'NONCE_KEY',         'lnQo/yr=rv@#N ~pl~3q#n:?GX,c`9Arhf<4B*fbqDDUD#xxeoGWl;72XDK[/t4,' );
define( 'AUTH_SALT',         ' &uW0bXOiyj,tFw%`nHml[#VCin*$T-!lKA/$o``WPs;NB(i_+?HN~cMh_Fkn~kE' );
define( 'SECURE_AUTH_SALT',  'E[Q3.5tA($;7fFKuoJZg[E<cXj*wJ=.3=]|/`F8i>.[}zl@I]M}LGV~&Yk Ue?dp' );
define( 'LOGGED_IN_SALT',    'JXH%<{<Q`6X3QJ>omW1(QmCo nnsnARrI35tuwXe=I0KtsTnw+f|&&N@#!cwR3E>' );
define( 'NONCE_SALT',        '.<&XyItgD~!|vyG7u W@WJv& &>AkS,Bg0eG?t%l#2Hafl2zmXd39pZ# EX3Q+~<' );
define( 'WP_CACHE_KEY_SALT', 'fM-^MYVx5k,:LXN4*%B2!GBsK^Q7h`PWw]8)tdi%oRglxEp@s fs[=IG>/m>Wn#k' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_REDIS_PREFIX', 'v3.feliz7play.com:' );
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );
define( 'CONCATENATE_SCRIPTS', false );
define( 'WP_POST_REVISIONS', '10' );
define( 'MEDIA_TRASH', true );
define( 'EMPTY_TRASH_DAYS', '15' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
define( 'WP_REDIS_DISABLE_BANNERS', true );

define('WP_SITEURL', 'https://v3.feliz7play.com');
define('WP_HOME', 'https://v3.feliz7play.com');

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);          // subdiretórios
define('DOMAIN_CURRENT_SITE', 'v3.feliz7play.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

// Se estiver atrás de ALB/CloudFront com TLS:
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
  $_SERVER['HTTPS'] = 'on';
}
define('FORCE_SSL_ADMIN', true);


define('AS3CF_SETTINGS', serialize(array(
	'provider' => 'aws',
	'region' => 'us-east-1',
	'access-key-id' => $_ENV['WP_S3_ACCESS_KEY'],
	'secret-access-key' => $_ENV['WP_S3_SECRET_KEY'],
	'bucket' => $_ENV['WP_S3_BUCKET'],
    'enable-delivery-domain' => true,
    'delivery-domain' => $_ENV['WP_S3_BUCKET'],
    'signed-urls-object-prefix' => 'feliz7play',
    'force-https' => true,
    'remove-local-file' => true,
)));

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';


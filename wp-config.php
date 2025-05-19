<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'wordpress' );

/** Database password */
define( 'DB_PASSWORD', 'wordpress' );

/** Database hostname */
define( 'DB_HOST', 'database' );

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
define('AUTH_KEY',         'ZTGyUM-k+|?|I{|bJ0qi Z8hXi1wA,_C*OK/y`Y*`]Y|UopS2I4li`dEQvCD!yn!');
define('SECURE_AUTH_KEY',  ']Y(NuOE+a.`iIAaad5e,r7T@7Qb+1FH%#@@<=P-H2T:GY|O$Z-)JFW!LL4L}efOW');
define('LOGGED_IN_KEY',    '<f,lk&w)K6X}Vv/QaBiv_OvMKD<jci9z52GxKI2eYJiHfe2/Q&m/}}UkP=abG4i~');
define('NONCE_KEY',        'x:9Xir]Xc:g1NdMCt+9-e-iV=zbSh2>_%ftx>GTW=,+O4xL5,`AI/}:K(RV-d|q+');
define('AUTH_SALT',        'Ony<wk^Q9+7;6qEmR-r c7}(+S~4yW9 v-vv,c5P-!8|/$|<|cn6%g_k;C?4|`;H');
define('SECURE_AUTH_SALT', '%1xJXj-SBHyV(^E=<DOMH@y*0RTe-o?lD:hT{ZDrKXdPBk4D&z?$7$DHCX3yZ?4:');
define('LOGGED_IN_SALT',   'k5.wG6PFw) eiCqPWEw41=in2Y]ieW@sy*nuG~RPIi [&non5k*)F|.R./<|]CKV');
define('NONCE_SALT',       'bwr)O<6AOpk`+hdhZbH)(1Zc*WS$5Egqzk~^+vc1@cRLb,:R!nVZc[c[YKXy]=*E');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

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
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          'lINuyDgWZ;.I{)9ixB&->I#nQ+74cKpZLvJbDVq),}]jXYw)=(1NOnjdwc!evC#1' );
define( 'SECURE_AUTH_KEY',   'anIm05`[)a#0Kg8p&cMb7+cpLtBhn|_bJgT&4W~{-+6OY07(gUUF@_PFCZ)equ0.' );
define( 'LOGGED_IN_KEY',     '0et34-:_pvO@[29-=#iiKIT{ZJ8)7+p}JtL:Fl)ZQ3PPO ?@H;C95RU&XB$R`i]C' );
define( 'NONCE_KEY',         ';Zd/2/5Xz3-ugo|pr5[7Fb1}m`RoE=8t4=WC*$3Mu%J0-cObNbn`rQ+.^F`P+vCS' );
define( 'AUTH_SALT',         'Ar?xI7P]@TT`.>0HIp>bA6`>W2:,|JgUM0.T8Q0^.v8OLGW%=-/3mM/R#NIxCnWq' );
define( 'SECURE_AUTH_SALT',  'Voi<n6 w*.?x-[2LrYZ]~;(d]%yVTg fL7!{Ri+ HPUze:vc MICbt7rN0%UkjXD' );
define( 'LOGGED_IN_SALT',    'K6W&1_Z J~2?B-UvViTe)G-]ME[TFcB?FYyv58:oewmzp07Cu)Kc%QZnhJB@je<B' );
define( 'NONCE_SALT',        '5xR*}_[/iS7:Rbtd0bMcs&LlzVpt+9vH(!xtU2v3i0oXiD!Iix,x,a]+O>SZ(i~L' );
define( 'WP_CACHE_KEY_SALT', 'RAJ@Aj5B`/Ckl/!pl]g@G2#xuxu=7VYOEY]Lb),_dFb9!FL*ScQ{Vkc]-;!CG:_6' );


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

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'KNiOB!t$E_et1L^gVewFm0rrV,>Vvv{;!BJS!9Gz3kZs,y{TK_(qF17?Be?q0Ml-' );
define( 'SECURE_AUTH_KEY',  'D}fbq)tA7OWJ^xY3zE}6ZFgP[dfny2^[L,]p:<~3>8xy/Zm@?[z0?1pJb%roCUKn' );
define( 'LOGGED_IN_KEY',    'zgzth1@Q8I0[2R=KC ,0U]xC>-wd@mz1(EuLl:iq<jCE}tV^&,A7qGoO?2mn,iF1' );
define( 'NONCE_KEY',        '|2F8iP@K>A8uf5).5us;;3Wf~7Bji+a]]U4.Vl8BGM6OaC(A43.E6C#-wIRLR(Zv' );
define( 'AUTH_SALT',        ')-3BrZ:oza0yAt?)==[:^!P s7*|;ux$rPp+vb0#U)_M3*^h4nGkWKP~r|Ku!zMO' );
define( 'SECURE_AUTH_SALT', 'WXko)?wr7P!4R]W6Lb]Ud= }Nx#i|O-DrtPyi<kilhe6#.f/8r|xzQ3jnRCI`83-' );
define( 'LOGGED_IN_SALT',   '=Bdm%7|EuP(KSpWk062Po4,LSb|e&2z(qK.26B&|1T45Xa0wOQ13Wh!}HAD;ZFQw' );
define( 'NONCE_SALT',       'ETe=6W9DV6wTZS#!&@WzbdQ<Ae291`^t!&)]{[<$_(^,6Oy] o3A!m(<OF9906Db' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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

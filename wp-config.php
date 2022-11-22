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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wileocom_wp2_local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         '*/[zG)[k3XKkTc),Z2lOn2CE[5ov^TIHS=-<5 =wbv9<j3A{Ecw)=Ts[Y)w7peFh' );
define( 'SECURE_AUTH_KEY',  'KA.Jy:jC6P0eNxCCEGCM9!6dwj!+q.V%B):i2H}~k.ptK;_I3M4.%=f}tpc8Dx&d' );
define( 'LOGGED_IN_KEY',    '*6@T6V!w>m[y jH%[:AllVT<#Yh;t{[j(l2B9w4xMqDRM4TrF+>nq RWLCmr+$aZ' );
define( 'NONCE_KEY',        'RuY7mdwq$4QH$H p!w:PZ+s$qGlx|K:Z|LLMh6vMs7p)8b+~^[8ha2]Cq54^Df<Z' );
define( 'AUTH_SALT',        '-wu);pA0@EGAe.<T*4,Ur9# Wou(->Opz>@IywROfS{B?jpa5[?LHiX+}B(,Ku{R' );
define( 'SECURE_AUTH_SALT', 'Mki@mLA^c+3fA+.qIHf>K~y|zbwj@XOC` SX{Dh5$l@{+d7ClNQFCMja6}O4jcd^' );
define( 'LOGGED_IN_SALT',   '#]N?UCarEctHm/[?d_{/z)|h]ewt7Q$BfNwkb&8ir-NPS[7 >u^}a-`oN]u,A{O%' );
define( 'NONCE_SALT',       'qVHzbaIHjz;}4MZWpTL{p^6{rlJy]<F96P_[/YzU}?f8SKg;UOR/-g&G//?LjTET' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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

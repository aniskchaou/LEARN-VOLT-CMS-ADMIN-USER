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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'learnvolt' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'nE=<UVBU% xmup(G&L?:p{4$C?vV&g1XhoA<&7LqJ(QL|s<Dh1dg2)uFhle<#x[f' );
define( 'SECURE_AUTH_KEY',  'ue,&p^M%/ngVoaT<[hEO4pYHkE*OIN9[^u)?_+;EwSGS@>tG`T;h*GjBUuG@aJw!' );
define( 'LOGGED_IN_KEY',    '`j&Sw}g0M>ImLRl1SrFU4z,JVK!ces<^SEL/oALJp3}oN@!v(DoS,zqq@3$b:dG_' );
define( 'NONCE_KEY',        'G_/#?exz/)z/?#,2emC7^  :x?yMH)X%~$[|2]G|Ko!$%nRX.LsLf%x ~3vj)KWL' );
define( 'AUTH_SALT',        'A;axTZ_?sRffD+k!GkH&}4S/ptK;DhHhY.3UrUiRX7v&70/F;?Bk^@Mp3#keij^[' );
define( 'SECURE_AUTH_SALT', '1)/% =M`#pRI4Lftv&X}B8rUyA4i>Dp)4jZ>Abj.Yj5RQI17!1.PiXh6Nx>=@*-P' );
define( 'LOGGED_IN_SALT',   'haD>25Meu`=0S6/I:$ESgwEL@OJ,jy&%Jy/W0Z$!fI?;:yLn-q/Dt)w&3p#?=kfX' );
define( 'NONCE_SALT',       '0=]Tv%$zw]`m67mgIO{ kV)Bs-w%9c8p/#I(FHdk DW>]n27?<.aXUPtupl:t%$T' );

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

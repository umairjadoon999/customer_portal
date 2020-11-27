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
define( 'DB_NAME', 'mint_portal' );

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
define( 'AUTH_KEY',         'STomQ=KQ!5mQN{z;$MFVu)r#CKBl_Uyr;D%T!m}f(lbVazx>vFHjvGnQ`:gq#pPx' );
define( 'SECURE_AUTH_KEY',  'm{W4ZvrlB6F~,44V)Az#pO!cZ9!~63X=D^=JRN/Jw/]nav8[2sQ?MGeSPe.2r(?>' );
define( 'LOGGED_IN_KEY',    'u]C`3qQ`!8F3K:dFr/r>xx?:nSwOOt%4zmN|wg~cYHO3U2%-u|?xJ/i-KLj-vn/G' );
define( 'NONCE_KEY',        'MQ<Q+7.GOC|:wUk{M?]AWn2l1R`A<b S WJQ{D6#A7iJagMv<&iq~a$ 9iY>`4,M' );
define( 'AUTH_SALT',        'VL$@U57-[m%no;;luYA^!qpG:Vf:gIn7zr$sdGPiSrye7@8_hobEw6wPk8_O8Kfo' );
define( 'SECURE_AUTH_SALT', 'nbFWEMw!`M5IcmS:tnE1rnVClV*(?rM6Uh[]_vG0O0.c 3yyGj9PY${CQ7i,BI;P' );
define( 'LOGGED_IN_SALT',   '!xm/w;D<xKl.hXrpNA91<D;e$#>v^)#ReehM0;~z=5yI,*2$30*}bwo{i?=8b3-Y' );
define( 'NONCE_SALT',       '/Vn)YHqP`/WW|YxS=zB7pFFoW&W|C6Qhqpxp0BY0N.K~$]:qrv;KY2OG,~`X?Ldp' );

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

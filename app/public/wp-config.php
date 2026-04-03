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
define( 'AUTH_KEY',          '*{<S@`[W:%2;?&7ot_SulM~GT>=dU7#V6;{]E6ig/yE?U8[/;6tu5e)=we]W^J)t' );
define( 'SECURE_AUTH_KEY',   '2cc*Oz4eSp}}&O9T<k3u9>l^HCC?vN9my ihkn7XfCDj#QCOO<3 !)k81/yis**j' );
define( 'LOGGED_IN_KEY',     'J1 TYhR2F#A$,j$Ng(%P<-,ln6hy8!ql+OFTB}Jz76>E;e{ D=qbR4LYlhwhlBF;' );
define( 'NONCE_KEY',         'd 0m BBuA&abEFkq*e`9Hv9nlGlcH=:Z-7K~Prm$=nR:]zsxH3wiY2saXN>-K+IF' );
define( 'AUTH_SALT',         '&4#-fB$%;<Ne6lm31RNr6|f<-`a5/XF7eNZ,)>a%p.3l0R}-tv5yyAhJacrgvp@D' );
define( 'SECURE_AUTH_SALT',  '}F&af/Vnf<$M.yWt^=bzq}N[?W<cSgNivn5Ixj[7T~)Jf-6eOw?Laj60cxf)aBYw' );
define( 'LOGGED_IN_SALT',    'w3}N)~p.=9 9UYE;N9M {}@KgIbN}lcWE:5a|1jdan_orp)f>tw^#b-HRT:ZOm-b' );
define( 'NONCE_SALT',        'L?-X?{&^o `6n2B=r{wiH2,a=[`Rke5$b{ZhAvigO^L1~$RRHD~Bvu&ed,Jagk5g' );
define( 'WP_CACHE_KEY_SALT', '&T/gHZMh:TTxO1I j2gVWeRFZcB=[$y@6k}uQ?@Cv:p,pGJa@1ma$npL}}qFGK Y' );


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

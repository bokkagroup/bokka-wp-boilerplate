<?php
# Database Configuration
if (strpos($_SERVER['HTTP_HOST'], 'myjoneshome.local') > -1) {
    define('DB_NAME', 'myjoneshome');
    define('DB_USER', 'wp');
    define('DB_PASSWORD', 'wp');
    define('DB_HOST', '127.0.0.1');
    define('DB_HOST_SLAVE', '127.0.0.1');
    define('DB_CHARSET', 'utf8');
    define('DB_COLLATE', 'utf8_unicode_ci');
    $table_prefix = 'wp_';
    define( 'DOMAIN_CURRENT_SITE', 'myjoneshome.local' );

} else {
    define('DB_NAME', 'wp_myjoneshome');
    define('DB_USER', 'myjoneshome');
    define('DB_PASSWORD', 'FO0i14IIAiL8MAdeeCN5');
    define('DB_HOST', '127.0.0.1');
    define('DB_HOST_SLAVE', '127.0.0.1');
    define('DB_CHARSET', 'utf8');
    define('DB_COLLATE', 'utf8_unicode_ci');
    $table_prefix = 'wp_';
    define( 'DOMAIN_CURRENT_SITE', 'myjoneshome.wpengine.com' );
}

# Security Salts, Keys, Etc
define('AUTH_KEY',         '7ymJJ{U` zj5N(yf#ty+/04HbL+;as^ND|rC3z~mv)${C!3t)$4[fU_:mwsdkF%+');
define('SECURE_AUTH_KEY',  'G,J8fma3>`$Ih^G69dzfFsUlIp@o9> -S}g|xJh7*vHm@zrv|FRe;6u2iQJp{>Q ');
define('LOGGED_IN_KEY',    'zkm}pdo1JcQf8[3[m|}kA2<6Wr/}j%<zs{Aa*@x;<XRjq7+N+8)Tc=w<,mSm0M7}');
define('NONCE_KEY',        'eG`1FVrsT0EQsd+MJr`S[?Wzb}z~p4XIKStPNN|J`ru6QYEp+A9M8k>YL%^ hYfk');
define('AUTH_SALT',        'Y~SUf)6@8b85|)/wv=z[{6qalknDwbOli+QNP1P6d^!Ti&u`KMkRE/u]j]lK%;Eg');
define('SECURE_AUTH_SALT', ':wh>~>~4:cQc.;GN<hf3.pGLV5,:p8G-cWdu)}bUsf6HaXF)W*u|_pX4QWxUb4=>');
define('LOGGED_IN_SALT',   '$Jt|8p/0H(VD.(MY^83JAc;(o >WaP%Q9[uy|VQTwHkg|DNm`1uk<B-%a(B-d1E1');
define('NONCE_SALT',       '=&9[7NqpdWoFNL[3RP4H;tzgu8YEVv2|&YEb5PC)OQ0cgO_gA@`[:jvg] *rLr+K');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'myjoneshome' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '58caade89cf87662502cc78fcb3c4a6a5af44504' );

define( 'WPE_CLUSTER_ID', '31006' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', true );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'myjoneshome.com', 1 => 'myjoneshome.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-31006', );

$wpe_special_ips=array ( 0 => '23.253.125.26', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', true );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );


#WP SMTP Settings
define('WPMS_ON', true);
define('WPMS_MAIL_FROM', 'From Email');
define('WPMS_MAIL_FROM_NAME', 'From Name');
define('WPMS_MAILER', 'smtp'); // Possible values 'smtp', 'mail', or 'sendmail'
define('WPMS_SET_RETURN_PATH', 'false'); // Sets $phpmailer->Sender if true
define('WPMS_SMTP_HOST', 'email-smtp.us-west-2.amazonaws.com'); // The SMTP mail host
define('WPMS_SMTP_PORT', 587); // The SMTP server port number
define('WPMS_SSL', ''); // Possible values '', 'ssl', 'tls' - note TLS is not STARTTLS
define('WPMS_SMTP_AUTH', true); // True turns on SMTP authentication, false turns it off
define('WPMS_SMTP_USER', 'AKIAIJMS7Z3LYDKS47CA'); // SMTP authentication username, only used if WPMS_SMTP_AUTH is true
define('WPMS_SMTP_PASS', 'AlEcqC5dEpOS0+hDS9EyOH9zsuVEmItmEeDSG/zjxbr9'); // SMTP authentication password, only used if WPMS_SMTP_AUTH is true

#Theme settings
define( 'WP_DEFAULT_THEME', 'bokka-wp-theme' );

##FORCE SSSL
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
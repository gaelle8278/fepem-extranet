<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'testdb');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'test_user');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'test_pwd');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'GBVTt&YN2h+.W|Wjl9)2pVP?$W|_#=`3lArU:ili]N0<3L|}a]<rUCO{B}OYu,%h');
define('SECURE_AUTH_KEY',  'g>8weYb~YSh6-Ab8!F<eJx/Djf-!F&}reZ$,`qVgZ,9c^|c^p/$Si$i^rAj9x:SL');
define('LOGGED_IN_KEY',    '%IpS;Oq]5mKsWh3(#im__AQD6Q$c#umqRBuuC}Y3=`#m[.:B$&!4}L#C ^R%V@X.');
define('NONCE_KEY',        'eAq)msfo*,(gv%TKSIwFZ+V?1X=*@SD,ZV`Z(Ccf6oLUbXDp}{UB55qd:W;aMJf<');
define('AUTH_SALT',        '#+|Kom~Y,.Wj%[H!D5?WRVIM*~VvvZL+$kJcHIm|S4]|}@aah{+wm!&7G0?b~<K4');
define('SECURE_AUTH_SALT', '}q<2C!mgJG|{-DDT`?<9YP{PMK}mq6FMPwSd T;$#5XHZQ7u1,f>nJ$UI?a3Cy :');
define('LOGGED_IN_SALT',   'qty8n{Wn@N7Lt%#Va&(PtP[b/*Xg~dG$s:2y)!;pp&&EDdQ<.Nrm6lPI}/Jx$l|z');
define('NONCE_SALT',       'ZKoKC=c.UN>g[e9dgY#JgB4NxGhvsE:I0/p^4YDRyCH4HlJ!KFI{NCQ^d5EL+(6h');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
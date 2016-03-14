<?php

/**
 * Plugin Name: NoIndex Env
 * Description: Permet de ne pas indexer les environnements de développement (balise meta robots no-index). Ce plugin peut être activé sans risque en production. Compatible avec Yoast SEO.
 * Author: GStein
 * Version: 1.0.0
 * Plugin Slug: wp-no-index-env
 * ---------------------------------
 * USAGE : Définir la constante ROBOTS_NOINDEX à true dans la config du site pour ne pas indexer l'instance
 * ==> define('ROBOTS_NOINDEX', true);
*/

class wp_no_index
{

	public function __construct(){

		/**
		 * Si la constante ENV_DEV n'est pas définie on ne fait rien
		 */
		if( ! defined('ROBOTS_NOINDEX') || ! ROBOTS_NOINDEX ){
			return;
		}

		/**
		 * Compatibilité avec Yoast Wordpress SEO
		 */
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if( is_plugin_active('wordpress-seo/wp-seo.php') ){
			add_action('plugins_loaded', array(&$this, 'add_yoast_filters'));
			return;
		}

		add_action('wp_head', array(&$this, 'hook_head_meta_noindex'));
	}

	public function add_yoast_filters(){
		add_filter('wpseo_robots', array(&$this, 'return_noindex'), 99999);
	}

	public function hook_head_meta_noindex(){
		?>
		<meta name="robots" content="noindex" />
		<?php
	}

	public function return_noindex($robotsstr){
		return 'noindex';
	}

}

$wp_no_index = new wp_no_index();
<?php
/*
Plugin Name: Template Endpoint
Plugin URI: https://www.facebook.com/groups/advancedwp/permalink/789725617756321/
Description: Load theme template from <code>theme-directory/templates/example.php</code> when <code>/template/example</code> is added to the end of a URL.
Version: 1.0
Author: Paul Clark
Author URI: http://pdclark.com
*/

PDC_Template_Endpoint::setup_hooks();

class PDC_Template_Endpoint {

	static public function setup_hooks() {
		register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );

		add_action( 'init', array( __CLASS__, 'init' ) );
		add_filter( 'template_include', array( __CLASS__, 'template_include' ) );
	}

	/**
	 * Flush rewrite rules when plugin activates.
	 */
	static public function activate() {
		self::init();
		flush_rewrite_rules();
	}

	/**
	 * Add /template/name/ endpoint to URLs for pages.
	 * Use EP_PAGES instead of EP_ALL to restrict to pages only.
	 * See link below for alternate URL masks.
	 *
	 * @see  https://make.wordpress.org/plugins/2012/06/07/rewrite-endpoints-api/
	 */
	static public function init() {
		add_rewrite_endpoint( 'template', EP_ALL );
	}

	/**
	 * Select template from theme directory if endpoint is set and file exists.
	 * For example, /page-name/template/example/ will load theme/templates/example.php
	 */
	static public function template_include( $template ) {
		global $wp_query;

		if ( ! empty( $wp_query->query_vars['template'] ) ) {
			$file = 'templates/' . $wp_query->query_vars['template'] . '.php';
			$new_template = locate_template( array( $file ) );
			if ( ! empty( $new_template ) ) {
				return $new_template;
			}
		}

		return $template;
	}

}
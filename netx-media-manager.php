<?php
/**
 * @package NetxWPPlugin
 */
/*
Plugin Name: NetX Media Manager
Plugin URI: http://netx.net
Description: Sync the Wordpress media library with NetX
Version: 2.1.5
Author: NetX, PNDLM
Author URI: http://netx.net
License:
*/

define('WPNETX_VERSION', '2.1.5');
define('WPNETX_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NETX_PLUGIN_URL', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));

//error_reporting(-1);
//ini_set('display_errors', 'On');

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
	echo "nothing to see here";
	exit;
}

/**
 * NetX REST API Library wrapper class
 */
require_once(dirname(__FILE__) . '/includes/netxRestWrapper.php');

/**
 * Admin class
 */
if (is_admin()) {
	require_once(dirname(__FILE__) . '/admin.php');
	require_once(dirname(__FILE__) . '/netx-media.php');
} else {
	require_once(dirname(__FILE__) . '/netx-client.php');
}

/**
 * Wordpress plugin functionality
 *
 * @package NetxWPPlugin
 */
class netxMediaManagerPlugin {
	/**
	 * Unregister plugin settings
	 */
	static function unregisterSettings() {
		unregister_setting('netx_options', 'netx_username');
		unregister_setting('netx_options', 'netx_password');
		unregister_setting('netx_options', 'netx_uri');
		unregister_setting('netx_options', 'netx_uri_protocal');
		unregister_setting('netx_options', 'netx_base_category_id');
		unregister_setting('netx_options', 'netx_base_category_path');
		unregister_setting('netx_options', 'netx_access_token');
	}

	/**
	 * Runs when plugin is deactivated. Removes the media manager table
	 * and the schema version option.
	 */
	static function deactivate() {
		error_log("Calling deactivate()");
		self::unregisterSettings();

		delete_option('netx_options');
	}

	/**
	 * Runs when the plugin is activated.
	 */
	static function activate() {
		$cacheDir = dirname(__FILE__) . '/netx_cache';
		$options['netx_cache_path'] = $cacheDir;
		add_option('netx_options', $options);
	}
}

// Plugin activation / deactivation
register_activation_hook(__FILE__, array('netxMediaManagerPlugin', 'activate'));
register_deactivation_hook(__FILE__, array('netxMediaManagerPlugin', 'deactivate'));

?>

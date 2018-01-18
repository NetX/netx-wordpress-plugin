<?php

class NetxClient {
	public static function init() {
		new NetxClient();
	}

	public function __construct() {
		//include our client side code
		add_action('wp_enqueue_scripts', array($this, 'setup_client_scripts'));
	}

	public function setup_client_scripts() {
		wp_enqueue_script('netx_client_script', plugins_url('scripts/clientScript.js', __FILE__));
		wp_localize_script('netx_client_script', 'netxScript', array(
			'debug' => "0"
		));
	}
}


add_action('init', array('NetxClient', 'init'));
?>
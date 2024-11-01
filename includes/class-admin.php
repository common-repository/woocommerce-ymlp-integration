<?php

class WC_YMLP_Admin {
	
	public function __construct() {
		add_action('plugins_loaded', array($this, 'load_integration') );
        add_filter('woocommerce_integrations', array($this, 'add_integration') );
	}

	public function load_integration()
	{
		require WC_YMLP_PLUGIN_DIR . 'includes/class-integration.php';
	}

	public function add_integration($integrations)
	{
		$integrations[] = 'WC_YMLP_Integration';
		return $integrations;
	}
}
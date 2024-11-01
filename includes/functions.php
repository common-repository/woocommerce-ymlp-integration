<?php

function wc_ymlp_get_settings() {
	static $settings;

	if(!$settings) {
		$defaults = array(
			'license_key' => '',
			'precheck' => 0,
			'label_text' => "Sign up to the newsletter.",
			'api_key' => '',
			'username' => '',
			'load_css' => 0,
			'group' => 1,
			'position' => 'after_customer_details'
		);

		$settings = get_option('woocommerce_ymlp_settings', array());
		$settings = wp_parse_args($settings, $defaults);
	}

	return $settings;
}

function wc_ymlp_get_api()
{
	static $api;

	if(!$api) {
		$s = wc_ymlp_get_settings();
		require_once WC_YMLP_PLUGIN_DIR . 'includes/class-api.php';
		$api = new WC_YMLP_API($s['api_key'], $s['username']);
	}

	return $api;
}
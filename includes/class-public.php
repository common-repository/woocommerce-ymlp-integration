<?php

class WC_YMLP_Public {
	
	public function __construct()
	{

		$s = wc_ymlp_get_settings();

		// Only add hooks if plugin is properly configured
		if(empty($s['api_key']) || empty($s['username'])) {
			return;
		}	

		if($s['position'] == 'after_shipping_form') {
			$hook ='woocommerce_checkout_shipping';
		} elseif($s['position'] == 'after_order_review') {
			$hook ='woocommerce_checkout_order_review';
		} elseif($s['position'] == 'after_billing_form') {
			$hook ='woocommerce_checkout_billing';
		} else {
			$hook ='woocommerce_checkout_after_customer_details';
		}

		add_action($hook, array($this, 'output_checkbox'), 11);

		if($s['load_css']) {
			add_action( 'wp_enqueue_scripts', array($this, 'load_css') );
		}

	}

	public function load_css()
	{
		$checkout_page_ID = get_option('woocommerce_checkout_page_id');

		// only load stylesheet on checkout page
		if(get_the_ID() == $checkout_page_ID) {
			wp_enqueue_style( 'wc-ymlp-checkbox-reset', WC_YMLP_PLUGIN_URL . 'assets/css/checkbox.css' );
		}
	}

	public function output_checkbox()
	{
		$s = wc_ymlp_get_settings();

		$label = __($s['label_text']);
		?>
		<p id="wc-ymlp-checkbox-wrapper"><label for="wc-ymlp-checkbox"><input type="checkbox" name="wc-ymlp-subscribe" value="1" id="wc-ymlp-checkbox" <?php checked($s['precheck'], "yes"); ?> /><?php echo $label; ?></label></p>
		<?php
	}	

}
<?php

class WC_YMLP_Plugin {
	
	public function __construct()
	{
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'save_checkbox_value' ) );
		add_action( 'woocommerce_order_status_changed', array( $this, 'subscribe' ), 10, 3 );
	}


	public function save_checkbox_value( $order_id )
	{
		if( !isset( $_POST['wc-ymlp-subscribe'] ) || $_POST['wc-ymlp-subscribe'] != 1 ) {
			return false;
		}
		
		update_post_meta( $order_id, 'ymlp_optin', true);
	}

	public function subscribe( $order_id, $status, $new_status ) {
		
		$order = new WC_Order( $order_id );

		$do_optin = (isset($order->order_custom_fields['ymlp_optin'][0]) && $order->order_custom_fields['ymlp_optin'][0]);

		if(!$do_optin) { 
			return false;
		}

		$email = $order->billing_email;

		$s = wc_ymlp_get_settings();
		$api = wc_ymlp_get_api();

		$group_id = $s['group'];

		$result = $api->subscribe($email, $group_id);

		if($result === true) {
			$delete = delete_post_meta( $order_id, 'ymlp_optin');
		}
		
		return $result;

	}

}
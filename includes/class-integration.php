<?php

class WC_YMLP_Integration extends WC_Integration
{

	public $id = 'ymlp';
	public $method_title = 'YMLP Settings';
	public $method_description = 'This extension shows a YMLP sign-up checkbox at your checkout form. Configure the checkbox below.';

	public function __construct()
	{
		$this->init_form_fields();

		add_action( 'woocommerce_update_options_integration_ymlp', array( $this, 'process_admin_options' ) );
	}

	/**
     * Initialise Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {

    	$s = wc_ymlp_get_settings();

    	$fields = array();

		$fields['api_key'] = array(
			'title' => __('YMLP API Key', 'edd_ymlp'),
			'description' => __('Enter your YMLP API key, found by going to <strong>Configuration > Api</strong> in your YMLP panel.', 'edd_ymlp'),
			'type' => 'text',
			'size' => 'regular',
			'default' => $s['api_key']
		);

		$fields['username'] = array(
			'title' => __('YMLP Username', 'edd_ymlp'),
			'description' => __('Enter your YMLP username.', 'edd_ymlp'),
			'type' => 'text',
			'size' => 'regular',
			'default' => $s['username']
		);

		$fields['label_text'] = array(
			'title' => __('Label Text', 'edd_ymlp'),
			'description' => __('Text shown next to the checkbox', 'edd_ymlp'),
			'type' => 'text',
			'size' => 'regular',
			'default' => $s['label_text']
		);

		$fields['precheck'] = array(
			'title' => __('Pre-check the checkbox', 'edd_ymlp'),
			'description' => 'Check this if you want the checkbox to be checked by default',
			'type' => 'checkbox',
			'size' => 'regular',
			'default' => $s['precheck']
		);

		$fields['position'] = array(
			'title' => __('Checkbox position', 'edd_ymlp'),
			'description' => 'Where do you want the checkbox to show up?',
			'type' => 'select',
			'options' => array(
				'after_billing_form' => 'After the billing address form',
				'after_shipping_form' => 'After the shipping address form',
				'after_order_review' => "After the order details form",
				"after_customer_details" => "After the customer details (billing & shipping)"
			),
			'default' => $s['position']
		);

		$fields['load_css'] = array(
			'title' => __('Load some default CSS?', 'edd_ymlp'),
			'description' => __('Check this if the checkbox appears in a weird place.', 'edd_ymlp'),
			'type' => 'checkbox',
			'size' => 'regular',
			'default' => $s['load_css']
		);
		
		if(!empty($s['api_key']) && !empty($s['username'])) {
			$fields['group'] = array(
				'title' => __('Group', 'edd_ymlp'),
				'description' => 'Select group to which subscribers should be added.',
				'type' => 'select',
				'size' => 'regular',
				'options' => $this->get_group_options(),
				'default' => $s['group']
			);
		}

    	$this->form_fields = $fields;

    }

    public function get_group_options()
	{
		// first, try to get from transient
		$group_options = get_transient('wc_ymlp_groups');
		if($group_options) { return $group_options; }

		// transient failed, try to get from api
		$groups = wc_ymlp_get_api()->get_groups();

		if($groups && is_array($groups)) {

			$group_options = array();

			foreach($groups as $g) {
				$group_options[$g->ID] = "{$g->GroupName} ({$g->NumberOfContacts})";
			}

			// store in transients
			set_transient('wc_ymlp_groups', $group_options, (24 * 3600)); // 1 day
			set_transient('wc_ymlp_groups_fallback', $group_options, (14 * 24 * 3600)); // 2 weeks
			return $group_options;
		}

		// api failed, get from older transient
		$group_options = get_transient('wc_ymlp_groups_fallback');
		if($group_options) { return $group_options; }

		// even the older transient failed, return the default group ID
		return array('1' => "Default group");
	}

}
<?php

class WC_YMLP_API 
{

	private $api_key;
	private $username;
	private $url = "https://www.ymlp.com/api/";

	public function __construct($api_key, $username)
	{
		$this->api_key = $api_key;
		$this->username = $username;
	}

	public function subscribe($email, $group_ID = 1)
	{
		$method = "Contacts.Add";

		$params = array(
			'Email' => $email,
			'GroupID' => $group_ID
		);

		$result = $this->call($method, $params);

		if($result && isset($result->Code) && $result->Code == 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_groups()
	{
		$method = "Groups.GetList";
		$data = $this->call($method);
		return $data;
	}

	private function call($method, $params = array())
	{
		$params['Key'] = $this->api_key;
		$params['Username'] = $this->username;
		$params['Output'] = 'JSON';

		$url = $this->url . $method . '?' . http_build_query($params);

		$response = wp_remote_get($url);
		$body = wp_remote_retrieve_body($response);

		return json_decode($body);
	}
}
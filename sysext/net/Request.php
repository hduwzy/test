<?php

namespace sysext\net;

use flight\net\Request as F_Request;

class Request extends F_Request {


	public function getRouteString()
	{
		$str_parts = explode('/', trim($this->url, '/'));
		if (count($str_parts) != 3) {
			return false;
		}

		foreach ($str_parts as $key => $value) {
			if (empty($value)) {
				return false;
			}
		}

		if (($whatpos = strpos($str_parts[2], '?')) !== false) {
			$str_parts[2] = substr($str_parts[2], 0, $whatpos);
		}

		return array(
			'module' 		=> strtolower( $str_parts[0] ),
			'controller' 	=> strtolower( $str_parts[1] ),
			'action' 		=> strtolower( $str_parts[2] ),
		);
	}

	public function getQuery($key = '', $default = '') 
	{
		if (empty($key)) {
			return $this->query;
		}

		return isset($this->query[$key]) ? $this->query[$key] : $default;
	}

	public function getPost($key = '', $default = '')
	{
		if (empty($key)) {
			return $this->data;
		}

		return isset($this->data[$key]) ? $this->data[$key] : $default;
	}

	public function getCookie($key = '', $default = '')
	{
		if (empty($key)) {
			return $this->cookie;
		}

		return isset($this->cookie[$key]) ? $this->cookie[$key] : $default;
	}
}

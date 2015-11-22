<?php

namespace sysext\app;

class Conf {


	public 		$app;
	private 	$_conf_cache;

	public function getConfPath()
	{
		$path = $this->app->get('app.confpath', null);
		return empty($path) ? './conf' : $path;
	}

	private function _parseKey($key)
	{
		$key_parts = explode('.', $key);
		if (count($key_parts) < 2) {
			return false;
		}
		$key = array_pop($key_parts);
		$cache_key = implode('_', $key_parts);
		$conf_path = $this->getConfPath() . "/" . implode('/', $key_parts) . '.php';
		return array(
			'key' => $key,
			'cache_key' => $cache_key,
			'conf_path' => $conf_path
		);
	}

	public function get($key)
	{
		$parsed_key = $this->_parseKey($key);

		if (!isset($this->_conf_cache[$parsed_key['cache_key']])) {
			if (file_exists($parsed_key['conf_path'])) {
				$conf = require_once($parsed_key['conf_path']);
				$this->_conf_cache[$parsed_key['cache_key']] = $conf;
			} else {
				return null;
			}
		}
		
		$conf = $this->_conf_cache[$parsed_key['cache_key']];
		if ($parsed_key['key'] == '*') {
			return $conf;
		}

		return isset($conf[$parsed_key['key']]) ? $conf[$parsed_key['key']] : null;
	}
}
<?php

namespace sysext\db\cache;
use sysext\db\cache\CacheInterface;

class PDOMemcache implements CacheInterface {
	public function set()
	{
		return true;
	}

	public function get()
	{
		return false;
	}

	public function exists()
	{
		return false;
	}

	public function flush()
	{
		return false;
	}

	public function getKey()
	{
		return '';
	}
}
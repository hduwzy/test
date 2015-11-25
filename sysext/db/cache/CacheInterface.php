<?php

namespace sysext\db\cache;

interface CacheInterface {
	public function set();
	public function get();
	public function exists();
	public function flush();
	public function getKey();
}
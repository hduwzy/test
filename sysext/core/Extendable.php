<?php
namespace sysext\core;

interface Extendable{
	public function alias();
	public function before();
	public function after();
	public function register();
}
<?php

namespace sysext\app;

abstract class Controller {

	public $app;

	public function __construct($app)
	{
		$this->app = $app;
	}

	abstract public function before();
	abstract public function after();
}
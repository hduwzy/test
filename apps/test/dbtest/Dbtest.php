<?php

namespace apps\test\dbtest;
use sysext\app\Controller;
use flight\Flight;
class Dbtest extends Controller {

	public function before()
	{
		//echo "<h1>Before</h1>";
	}

	public function after()
	{
		// echo "<h1>After</h1>";
	}

	public function test()
	{
		echo "in test";
	}

	public function dbTest()
	{
		$app = $this->app;
		$ret = $app->db()->fetchAssoc("delete :table where", array('limit' => 3));
		echo "<pre>";
		print_r($ret);
		echo "</pre>";
	}

	public function dumpTableInfo()
	{
		$app = $this->app;
		$db = $app->db();

		$data = $db->fetchAll('sql.test.test.select.attr', array('cat_id' => 67, 'lang_flag' => 'en'));
		if (false === $data) {
			die($db->errorMsg());
		}
		echo "<pre>";
		print_r($data);
	}
}
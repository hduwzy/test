<?php
namespace apps\sqldoc\docs;
use sysext\app\Controller;
use flight\Flight;

class Show extends Controller {

	public function before()
	{
		//echo "<h1>Before</h1>";
	}

	public function after()
	{
		//echo "<h1>After</h1>";
	}

	public function test()
	{
		echo "in test";
	}

	public function docInfo()
	{
		$sqlkey = Flight::request()->getQuery('sqlkey');
		$sqlinfo = Flight::conf()->get($sqlkey);
		if (!$sqlinfo) {
			Flight::json(array('errcode' => -1, 'errormsg' => 'empty key'));
		}

		Flight::json($sqlinfo);
	}

	public function showsqlkey()
	{
		$sk = Flight::request()->getQuery('sk', '');
		$sk = '%' . implode('%', explode('|', $sk)) . '%';
		$sqlkey = Flight::db()->fetchAll(
			'sql.sqldoc.sqlkey.select.sqlkey', 
			array('sk' => $sk)
		);
		$this->app->json($sqlkey);
	}
}
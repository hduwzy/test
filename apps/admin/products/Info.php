<?php

namespace apps\admin\products;
use sysext\app\Controller;
use flight\Flight;
class Info extends Controller {

	public function before()
	{
		//echo "<h1>Before</h1>";
	}

	public function after()
	{
		echo "<h1>After</h1>";
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
		// $data = array();
		// $data[] = array('age' => 10, 'name' => 'tom');
		// $data[] = array('age' => 11, 'name' => 1);
		// $data[] = array('age' => 19, 'name' => 'lucy');
		// $data[] = array('age' => 1, 'name' => 'snow');
		// $data[] = array('age' => 100, 'name' => 'abc');
		// $db->fetchAffected('sql.test.test.delete.tbtest_all');
		
		// $data = $db->fetchAffected('sql.test.test.update.tbtest_by_name', array('age' => 111), array('name' => 'tom'));
		// print_r($data);
		// $data = $db->fetchAll('sql.test.test.select.tbtest_by_name', array('name' => 'tom'));
		// print_r($data);
		$data = $db->fetchAffected('sql.test.test.delete.tbtest_by_age', array('age' => 111));
		print_r($data);
		$data = $db->fetchAll('sql.test.test.select.tbtest_by_name', array('name' => 'tom'));
		print_r($data);
		// $db->fetchAffected('sql.test.test.insert.tbtest', array('age' => 10, 'name' => 'tom'));
		// $db->fetchAffected('sql.test.test.insert.tbtest', array('age' => 11, 'name' => 'lily'));
		// $db->fetchAffected('sql.test.test.insert.tbtest', array('age' => 19, 'name' => 'lucy'));
		// $db->fetchAffected('sql.test.test.insert.tbtest', array('age' => 1, 'name' => 'snow'));
		// $db->fetchAffected('sql.test.test.insert.tbtest', array('age' => 100, 'name' => 'abc'));
	}
}
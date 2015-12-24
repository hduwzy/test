<?php

define('FLIGHT_ROOT', dirname(dirname(__DIR__)) . "/includes/flight");
define('BOOT_FILE', FLIGHT_ROOT . "/boot/boot.php");

require_once(BOOT_FILE);

use rpc\Rpcclient;
use flight\Flight;


Flight::set('app.root', FLIGHT_ROOT);

$client = new Rpcclient();
$client->schema('tcp')->host('192.168.236.133')->port(7788);

// $client->call('test', array(1,2,3,4,5))->commit();

// for ($i=0; $i < 100000000; $i++) { 
	
// }

$status = $client->remoteLog(100);

print_r($status);

// $client->close();
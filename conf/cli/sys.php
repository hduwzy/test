<?php
use flight\Flight;
return array(
	'maxprocess' => 5,
	'localhost' => '0.0.0.0',
	'localport' => '7788',
	'phpexe' => '/usr/bin/php',
	'rpc_logpath' => Flight::get('app.root') . "/data/rpclog/"
);
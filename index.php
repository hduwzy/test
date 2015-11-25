<?php
require_once __DIR__.'/vendor/autoload.php';
use flight\Flight;
Flight::set('app.name', 'test');
Flight::set('app.root', dirname(__FILE__));
Flight::set('app.confpath', Flight::get('app.root') . '/conf');

require Flight::get('app.root') . '/sysext/extend.php';


Flight::start();
?>



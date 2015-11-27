<?php
require_once __DIR__.'/vendor/autoload.php';
use flight\Flight;
Flight::set('app.name', 'sqldoc');
Flight::set('app.root', dirname(__FILE__));
Flight::set('flight.views.path', Flight::get('app.root') . "/views");

require Flight::get('app.root') . '/sysext/extend.php';


Flight::start();
?>



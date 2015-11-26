<?php
define('ROOT', dirname(dirname(__DIR__)));
require_once ROOT.'/vendor/autoload.php';
use flight\Flight;
Flight::set('app.name', 'sqldoccli');
Flight::set('app.root', dirname(__FILE__));
Flight::set('app.confpath',ROOT . '/conf');

require_once(ROOT . '/sysext/extend.php');


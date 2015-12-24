<?php

define('FLIGHT_ROOT', dirname(dirname(__DIR__)) . "/includes/flight");
define('BOOT_FILE', FLIGHT_ROOT . "/boot/boot.php");

require_once(BOOT_FILE);

use rpc\Rpcserver;
use rpc\Rpcmaster;
use flight\Flight;
use cli\proc\Process;
use cli\events\EventInterface;

Flight::set('app.root', FLIGHT_ROOT);

Process::init();

$server = new Rpcserver();
$server->schema('tcp')							// 传输协议
	->host(Flight::conf()->get('cli.sys.localhost')) 		// 监听地址
	->port(Flight::conf()->get('cli.sys.localport'))		// 监听端口
	->create();

$rpc_master = new Rpcmaster();

Process::onSysEvent(
	$server->getSocket(),
	EventInterface::EV_READ,
	array($rpc_master, 'handle')
);

Process::loop();
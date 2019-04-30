<?php

use GoSwoole\BaseServer\ExampleClass\Server\DefaultServer;
use GoSwoole\Plugins\Mysql\MysqlPlugin;

require __DIR__ . '/../vendor/autoload.php';

define("ROOT_DIR", __DIR__ . "/..");

$server = new DefaultServer();
$server->getPlugManager()->addPlug(new MysqlPlugin());
//配置
$server->configure();
//启动
$server->start();

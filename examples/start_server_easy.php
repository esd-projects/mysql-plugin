<?php

use ESD\BaseServer\ExampleClass\Server\DefaultServer;
use ESD\Plugins\Mysql\MysqlPlugin;

require __DIR__ . '/../vendor/autoload.php';

define("ROOT_DIR", __DIR__ . "/..");
define("RES_DIR", __DIR__ . "/resources");
$server = new DefaultServer();
$server->getPlugManager()->addPlug(new MysqlPlugin());
//配置
$server->configure();
//启动
$server->start();

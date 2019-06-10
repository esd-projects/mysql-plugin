<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/22
 * Time: 14:36
 */

use ESD\Core\Channel\Channel;
use ESD\Core\DI\DI;
use ESD\Core\Plugins\Event\EventCall;
use ESD\Coroutine\Channel\ChannelFactory;
use ESD\Coroutine\Co;
use ESD\Coroutine\Event\EventCallFactory;
use ESD\Plugins\Mysql\MysqlOneConfig;
use ESD\Plugins\Mysql\MysqlPool;

require __DIR__ . '/../vendor/autoload.php';

Co::enableCo();
enableRuntimeCoroutine();
DI::$definitions = [
    Channel::class => new ChannelFactory(),
    EventCall::class => new EventCallFactory()
];

goWithContext(function () {
    $mysqlConfig = new MysqlOneConfig(
        'mysql-aliyun.dev.svc.cluster.local',
        'huiyi',
        'huiyi@123',
        'huiyi_callcenter',
        "t_");
    $mysqlPool = new MysqlPool($mysqlConfig);
    setContextValue("mysqlPool", $mysqlPool);

    goWithContext(function () {
        $mysqlPool = getDeepContextValueByClassName(MysqlPool::class);
        $db = $mysqlPool->db();
        var_dump($db->get("user", 1));
    });

    goWithContext(function () {
        $mysqlPool = getDeepContextValueByClassName(MysqlPool::class);
        $db = $mysqlPool->db();
        var_dump($db->get("user", 1));
    });
});
<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/22
 * Time: 14:36
 */

use ESD\Plugins\Mysql\MysqlConfig;
use ESD\Plugins\Mysql\MysqlPool;

require __DIR__ . '/../vendor/autoload.php';

enableRuntimeCoroutine();

goWithContext(function () {
    $mysqlConfig = new MysqlConfig(
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
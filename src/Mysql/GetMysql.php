<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/28
 * Time: 17:24
 */

namespace ESD\Plugins\Mysql;


trait GetMysql
{
    /**
     * @param string $name
     * @return MysqliDb
     * @throws MysqlException
     */
    public function mysql($name = "default")
    {
        $db = getContextValue("MysqliDb:$name");
        if ($db == null) {
            /** @var MysqlManyPool $mysqlPool */
            $mysqlPool = getDeepContextValueByClassName(MysqlManyPool::class);
            $pool = $mysqlPool->getPool($name);
            if ($pool == null) throw new MysqlException("没有找到名为{$name}的mysql连接池");
            return $pool->db();
        } else {
            return $db;
        }
    }
}
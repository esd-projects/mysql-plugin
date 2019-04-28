<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/4/28
 * Time: 17:24
 */

namespace GoSwoole\Plugins\Mysql;


trait GetMysql
{
    /**
     * @param string $name
     * @return \MysqliDb
     * @throws \GoSwoole\BaseServer\Exception
     */
    public function mysql($name = "default")
    {
        $db = getContextValue("MysqliDb:$name");
        if ($db == null) {
            $mysqlPool = getDeepContextValueByClassName(MysqlManyPool::class);
            if ($mysqlPool instanceof MysqlManyPool) {
                $db = $mysqlPool->getPool($name)->db();
                setContextValue("MysqliDb:$name", $db);
                return $db;
            } else {
                throw new MysqlException("没有找到名为{$name}的mysql连接池");
            }
        }
    }
}
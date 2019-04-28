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
        $mysqlPool = getDeepContextValueByClassName(MysqlManyPool::class);
        if ($mysqlPool instanceof MysqlManyPool) {
            return $mysqlPool->getPool($name)->db();
        }else{
            throw new MysqlException("没有找到名为{$name}的mysql连接池");
        }
    }
}
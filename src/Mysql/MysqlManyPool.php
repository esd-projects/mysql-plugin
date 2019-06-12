<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/23
 * Time: 10:59
 */

namespace ESD\Plugins\Mysql;


class MysqlManyPool
{
    protected $poolList = [];

    /**
     * 添加连接池
     * @param MysqlPool $mysqlPool
     */
    public function addPool(MysqlPool $mysqlPool)
    {
        $this->poolList[$mysqlPool->getMysqlConfig()->getName()] = $mysqlPool;
    }

    /**
     * @return MysqliDb
     * @throws MysqlException
     */
    public function db(): MysqliDb
    {
        $default = $this->getPool();
        if ($default == null) {
            throw new MysqlException("No default MySQL is set");
        }
        return $default->db();
    }

    /**
     * 获取连接池
     * @param $name
     * @return MysqlPool|null
     */
    public function getPool($name = "default")
    {
        return $this->poolList[$name] ?? null;
    }
}
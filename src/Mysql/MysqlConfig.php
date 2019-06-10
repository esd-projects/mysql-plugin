<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/23
 * Time: 10:49
 */

namespace ESD\Plugins\Mysql;

class MysqlConfig
{
    /**
     * @var MysqlOneConfig[]
     */
    protected $mysqlConfigs;

    /**
     * @return MysqlOneConfig[]
     */
    public function getMysqlConfigs(): array
    {
        return $this->mysqlConfigs;
    }

    /**
     * @param MysqlOneConfig[] $mysqlConfigs
     */
    public function setMysqlConfigs(array $mysqlConfigs): void
    {
        $this->mysqlConfigs = $mysqlConfigs;
    }

    public function addMysqlOneConfig(MysqlOneConfig $buildFromConfig)
    {
        $this->mysqlConfigs[$buildFromConfig->getName()] = $buildFromConfig;
    }
}
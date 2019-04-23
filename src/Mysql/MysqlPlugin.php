<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/23
 * Time: 10:30
 */

namespace GoSwoole\BaseServer\Plugins\Mysql;


use GoSwoole\BaseServer\Server\Context;
use GoSwoole\BaseServer\Server\Plugin\AbstractPlugin;

class MysqlPlugin extends AbstractPlugin
{
    /**
     * @var MysqlConfig[]
     */
    protected $configList = [];
    /**
     * 获取插件名字
     * @return string
     */
    public function getName(): string
    {
        return "Mysql";
    }

    /**
     * 在服务启动前
     * @param Context $context
     * @return mixed
     */
    public function beforeServerStart(Context $context)
    {
        return;
    }

    /**
     * 在进程启动前
     * @param Context $context
     * @return mixed
     * @throws \GoSwoole\BaseServer\Exception
     */
    public function beforeProcessStart(Context $context)
    {
        $mysqlManyPool = new MysqlManyPool();
        foreach ($this->configList as $config) {
            $mysqlPool = new MysqlPool($config);
            $mysqlManyPool->addPool($mysqlPool);
        }
        $context->add("mysqlPool", $mysqlManyPool);
    }

    /**
     * @return MysqlConfig[]
     */
    public function getConfigList(): array
    {
        return $this->configList;
    }

    /**
     * @param MysqlConfig[] $configList
     */
    public function setConfigList(array $configList): void
    {
        $this->configList = $configList;
    }
}
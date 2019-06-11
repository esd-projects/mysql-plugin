<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/23
 * Time: 10:30
 */

namespace ESD\Plugins\Mysql;

use Doctrine\Common\Annotations\AnnotationReader;
use ESD\Core\Context\Context;
use ESD\Core\PlugIn\AbstractPlugin;
use ESD\Core\PlugIn\PluginInterfaceManager;
use ESD\Core\Plugins\Logger\GetLogger;
use ESD\Core\Server\Server;
use ESD\Plugins\Aop\AopConfig;
use ESD\Plugins\Aop\AopPlugin;
use ESD\Plugins\Mysql\Aspect\MysqlAspect;

class MysqlPlugin extends AbstractPlugin
{
    use GetLogger;
    /**
     * @var MysqlConfig
     */
    protected $mysqlConfig;

    public function __construct()
    {
        parent::__construct();
        $this->mysqlConfig = new MysqlConfig();
        $this->mysqlConfig->setMysqlConfigs([]);
        AnnotationReader::addGlobalIgnoredName('params');
        $this->atAfter(AopPlugin::class);
    }

    /**
     * 获取插件名字
     * @return string
     */
    public function getName(): string
    {
        return "Mysql";
    }

    /**
     * @param Context $context
     * @return mixed|void
     * @throws \Exception
     */
    public function init(Context $context)
    {
        parent::init($context);
        $aopConfig = DIget(AopConfig::class);
        $aopConfig->addAspect(new MysqlAspect());
    }

    /**
     * @param PluginInterfaceManager $pluginInterfaceManager
     * @return mixed|void
     * @throws \DI\DependencyException
     * @throws \ESD\Core\Exception
     * @throws \ReflectionException
     */
    public function onAdded(PluginInterfaceManager $pluginInterfaceManager)
    {
        parent::onAdded($pluginInterfaceManager);
        $pluginInterfaceManager->addPlug(new AopPlugin());
    }

    /**
     * 在服务启动前
     * @param Context $context
     * @throws \Exception
     */
    public function beforeServerStart(Context $context)
    {
        //所有配置合併
        foreach ($this->mysqlConfig->getMysqlConfigs() as $config) {
            $config->merge();
        }
        $configs = Server::$instance->getConfigContext()->get(MysqlOneConfig::key, []);
        foreach ($configs as $key => $value) {
            $mysqlOneConfig = new MysqlOneConfig("", "", "", "");
            $mysqlOneConfig->setName($key);
            $this->mysqlConfig->addMysqlOneConfig($mysqlOneConfig->buildFromConfig($value));
        }
        $mysqliDbProxy = new MysqliDbProxy();
        $this->setToDIContainer(\MysqliDb::class, $mysqliDbProxy);
        $this->setToDIContainer(MysqliDb::class, $mysqliDbProxy);
        $this->setToDIContainer(MysqlConfig::class, $this->mysqlConfig);
    }

    /**
     * 在进程启动前
     * @param Context $context
     * @throws MysqlException
     * @throws \Exception
     */
    public function beforeProcessStart(Context $context)
    {
        $mysqlManyPool = new MysqlManyPool();
        if (empty($this->mysqlConfig->getMysqlConfigs())) {
            $this->warn("没有mysql配置");
        }
        foreach ($this->mysqlConfig->getMysqlConfigs() as $key => $value) {
            $mysqlPool = new MysqlPool($value);
            $mysqlManyPool->addPool($mysqlPool);
            $this->debug("已添加名为 {$value->getName()} 的Mysql连接池");
        }
        $context->add("mysqlPool", $mysqlManyPool);
        $this->setToDIContainer(MysqlManyPool::class, $mysqlManyPool);
        $this->setToDIContainer(MysqlPool::class, $mysqlManyPool->getPool());
        $this->ready();
    }

    /**
     * @return MysqlOneConfig[]
     */
    public function getConfigList(): array
    {
        return $this->mysqlConfig->getMysqlConfigs();
    }

    /**
     * @param MysqlOneConfig[] $configList
     */
    public function setConfigList(array $configList): void
    {
        $this->mysqlConfig->setMysqlConfigs($configList);
    }

    /**
     * @param MysqlOneConfig $mysqlOneConfig
     */
    public function addConfigList(MysqlOneConfig $mysqlOneConfig): void
    {
        $this->mysqlConfig->addMysqlOneConfig($mysqlOneConfig);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/10
 * Time: 12:20
 */

namespace ESD\Plugins\Mysql;


use ESD\Core\DB\DBInterface;
use ESD\Core\Plugins\Logger\GetLogger;
use ESD\Server\Co\Server;

class MysqliDb extends \MysqliDb implements DBInterface
{
    use GetLogger;

    public function __construct($host = null, $username = null, $password = null, $db = null, $port = null, $charset = 'utf8', $socket = null)
    {
        parent::__construct($host, $username, $password, $db, $port, $charset, $socket);
        $this->traceEnabled = Server::$instance->getServerConfig()->isDebug();
    }

    public function isTransactionInProgress(): bool
    {
        return $this->_transaction_in_progress ?? false;
    }

    /**
     * @return \MysqliDb
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function reset()
    {
        $result = parent::reset();
        if (Server::$instance->getServerConfig()->isDebug()) {
            $num = count($this->trace) - 1;
            $this->debug("Mysql query trace: " . $this->trace[$num][0] ?? null);
            $this->debug("Mysql query time: " . $this->trace[$num][1] * 1000 . " ms" ?? null);
        }
        return $result;
    }

    /**
     * @param string $connectionName
     * @throws \Exception
     */
    public function connect($connectionName = 'default')
    {
        parent::connect($connectionName);
        $this->debug("mysql connect $connectionName");
    }

    /**
     * @param string $connection
     * @throws \Exception
     */
    public function disconnect($connection = 'default')
    {
        parent::disconnect($connection);
        $this->debug("mysql disconnect $connection");
    }

    public function getType(): string
    {
        return "mysqli";
    }


    public function rawQuery($query, $bindParams = null)
    {
        return $this->execute($query, function () use ($query) {
            parent::rawQuery($query);
        });
    }

    public function rollback()
    {
        return $this->execute("ROLLBACK", function () {
            parent::rollback();
        });
    }

    public function commit()
    {
        return $this->execute("COMMIT", function () {
            parent::commit();
        });
    }

    public function startTransaction()
    {
        return $this->execute("BEGIN", function () {
            parent::startTransaction();
        });
    }

    /**
     * 执行代理
     * @param $query
     * @param callable|null $call
     * @return mixed
     */
    public function execute($query, callable $call = null)
    {
        return $call();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/10
 * Time: 12:20
 */

namespace ESD\Plugins\Mysql;


use ESD\Psr\DB\DBInterface;
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

    public function getType()
    {
        return "mysqli";
    }

    public function replace($tableName, $insertData)
    {
        return $this->execute(function () use ($tableName, $insertData) {
            return parent::replace($tableName, $insertData);
        });
    }

    public function insert($tableName, $insertData)
    {
        return $this->execute(function () use ($tableName, $insertData) {
            return parent::insert($tableName, $insertData);
        });
    }

    public function delete($tableName, $numRows = null)
    {
        return $this->execute(function () use ($tableName, $numRows) {
            return parent::delete($tableName, $numRows);
        });
    }

    public function update($tableName, $tableData, $numRows = null)
    {
        return $this->execute(function () use ($tableName, $tableData, $numRows) {
            return parent::update($tableName, $tableData, $numRows);
        });
    }

    public function get($tableName, $numRows = null, $columns = '*')
    {
        return $this->execute(function () use ($tableName, $numRows, $columns) {
            return parent::get($tableName, $numRows, $columns);
        });
    }

    public function query($query, $numRows = null)
    {
        return $this->execute(function () use ($query, $numRows) {
            return parent::query($query, $numRows);
        });
    }

    public function rawQuery($query, $bindParams = null)
    {
        return $this->execute(function () use ($query, $bindParams) {
            return parent::rawQuery($query, $bindParams);
        });
    }

    public function rollback()
    {
        $this->_lastQuery = "ROLLBACK";
        return $this->execute(function () {
            return parent::rollback();
        });
    }

    public function commit()
    {
        $this->_lastQuery = "COMMIT";
        return $this->execute(function () {
            return parent::commit();
        });
    }

    public function startTransaction()
    {
        $this->_lastQuery = "BEGIN";
        return $this->execute(function () {
            parent::startTransaction();
        });
    }

    /**
     * 执行代理
     * @param callable|null $call
     * @return mixed
     */
    public function execute(callable $call = null)
    {
        if ($call != null) {
            return $call();
        }
    }
}
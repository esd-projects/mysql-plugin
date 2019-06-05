<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/10
 * Time: 12:20
 */

namespace ESD\Plugins\Mysql;


use ESD\Core\Exception;
use ESD\Core\Plugins\Logger\GetLogger;
use ESD\Server\Co\Server;

class MysqliDb extends \MysqliDb
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
     * A method to connect to the database
     *
     * @param null|string $connectionName
     * @throws \Exception
     * @return void
     */
    public function connect($connectionName = 'default')
    {
        if (!isset($this->connectionsSettings[$connectionName]))
            throw new Exception('Connection profile not set');

        $pro = $this->connectionsSettings[$connectionName];
        $params = array_values($pro);
        $charset = array_pop($params);

        if ($this->isSubQuery) {
            return;
        }

        if (empty($pro['host']) && empty($pro['socket'])) {
            throw new Exception('MySQL host or socket is not set');
        }

        $mysqli = new Mysqli($params);

        if ($mysqli->connect_error) {
            throw new Exception('Connect Error ' . $mysqli->connect_errno . ': ' . $mysqli->connect_error, $mysqli->connect_errno);
        }

        if (!empty($charset)) {
            $mysqli->set_charset($charset);
        }
        $this->_mysqli[$connectionName] = $mysqli;
        $this->debug("mysql connect $connectionName");
    }

    /**
     * @return \MysqliDb
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
}
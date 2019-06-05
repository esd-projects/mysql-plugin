<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/6/4
 * Time: 18:31
 */

namespace ESD\Plugins\Mysql;


use ESD\Psr\DB\DBInterface;
use ReflectionClass;

class Mysqli implements DBInterface
{
    /**
     * @var
     */
    private $_mysqli;

    private $_lastQuery;

    /**
     * Mysqli constructor.
     * @param $params
     * @throws \ReflectionException
     */
    public function __construct($params)
    {
        $mysqlic = new ReflectionClass('mysqli');
        $this->_mysqli = $mysqlic->newInstanceArgs($params);
    }

    public function __call($name, $arguments)
    {
        $this->_lastQuery = json_encode($arguments);
        return $this->execute($name, function () use ($name, $arguments) {
            return call_user_func_array([$this->_mysqli, $name], $arguments);
        });
    }

    public function __set($name, $value)
    {
        $this->_mysqli->$name = $value;
    }

    public function __get($name)
    {
        return $this->_mysqli->$name;
    }

    public function getType()
    {
        return "mysqli";
    }

    public function execute($name, callable $call = null)
    {
        if ($call != null) {
            return $call();
        }
    }

    public function getLastQuery()
    {
        return $this->_lastQuery;
    }
}
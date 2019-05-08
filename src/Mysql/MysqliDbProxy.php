<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/8
 * Time: 10:12
 */

namespace GoSwoole\Plugins\Mysql;


class MysqliDbProxy
{
    use GetMysql;
    public function __get($name)
    {
        return $this->mysql()->$name;
    }

    public function __set($name, $value)
    {
        $this->mysql()->$name = $value;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->mysql(), $name], $arguments);
    }
}
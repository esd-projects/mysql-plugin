<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/10
 * Time: 12:20
 */

namespace GoSwoole\Plugins\Mysql;


class MysqliDb extends \MysqliDb
{
    public function isTransactionInProgress(): bool
    {
        return $this->_transaction_in_progress ?? false;
    }
}
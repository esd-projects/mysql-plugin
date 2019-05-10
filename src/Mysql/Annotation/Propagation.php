<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/10
 * Time: 10:16
 */

namespace GoSwoole\Plugins\Mysql\Annotation;

/**
 * 传播行为
 * Class Propagation
 * @package GoSwoole\Plugins\Mysql\Annotation
 */
class Propagation
{
    /**
     * 如果当前存在事务，则加入该事务；
     * 如果当前没有事务，则创建一个新的事务。
     */
    const REQUIRED = 0;
    /**
     * 如果当前存在事务，则加入该事务；
     * 如果当前没有事务，则以非事务的方式继续运行。
     */
    const SUPPORTS = 1;
    /**
     * 如果当前存在事务，则加入该事务；
     * 如果当前没有事务，则抛出异常。
     */
    const MANDATORY = 2;
    /**
     * 创建一个新的事务，如果当前存在事务，则把当前事务挂起。
     */
    const REQUIRES_NEW = 3;
    /**
     * 以非事务方式运行，如果当前存在事务，则把当前事务挂起。
     */
    const NOT_SUPPORTED = 4;
    /**
     * 以非事务方式运行，如果当前存在事务，则抛出异常。
     */
    const NEVER = 5;
    /**
     * 如果当前存在事务，则创建一个事务作为当前事务的嵌套事务来运行；
     * 如果当前没有事务，则该取值等价于 REQUIRED 。
     */
    const NESTED = 6;
}
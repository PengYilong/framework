<?php
use zero\Container;

if(!function_exists('app')) {

    /**
     * 快速获取容易中的实力 支持依赖注入
     *
     * @param string  $name  类名标识
     * @param array   $args   
     * @param boolean $newInstance 是否每次创建新的实例
     * @return void
     */
    function app($name = 'zero\Application', array $args = [], bool $newInstance = false) 
    {
        return Container::get($name, $args, $newInstance);
    }
}
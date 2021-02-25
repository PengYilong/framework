<?php
use zero\Container;
use zero\Response;
use zero\response\Json;

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

if(!function_exists('request')) {

    function request()
    {
        return app('request');
    }
}

if(!function_exists('json')) {

    /**
     * 获取zero\response\Json对象
     *
     * @param string $data  输出数据
     * @param string $type  输出类型
     * @param integer $code 
     * @return Response
     */
    function json($data = '', int $code = 200): Json
    {   
        return Response::create($data, 'json', $code);
    }
}
<?php
declare(strict_types = 1);

namespace zero;

use Closure;
use InvalidArgumentException;
use zero\Request;

class Middleware
{
    protected $queue = [];
    protected $config = [
        'default_namesapce' => 'app\\http\\middleware\\',
    ];

    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    public function import(array $middlewares, string $type = 'route')
    {
        foreach($middlewares as $middleware) {
            $this->register($middleware, $type);
        }       
    }

    /**
     * @param $key 
     * @param $type string
     */
    public function register($middleware, string $type = 'route'): bool
    {
        if( is_null($middleware) ){
            return false;
        }

        $middleware = $this->buildMiddleware($middleware, $type);
        
        if($middleware) {
            $this->queue[$type][] = $middleware;
        }   

        return true;
    }

    /**
     * 解析中间件
     *
     * @param mixed $middleware
     * @param string $type
     * @return void
     */
    protected function buildMiddleware($middleware, string $type = 'route'): array
    {
        if(is_array($middleware)) {
            list($middleware, $params) = $middleware;
        }

        if($middleware instanceof Closure) {
            return [$middleware, $params ?? null];
        }

        if( !is_string($middleware) ) {
            throw new InvalidArgumentException('The middleware is invalid:'. $middleware);
        }

        if(false === strpos($middleware, '\\')) {
            $middleware = $this->config[$middleware] ?? $this->config['default_namesapce'] . $middleware;
        }

        return [[new $middleware, 'handle'], $params ?? null];
    }

    /**
     * 注册控制器中间件
     *
     * @param mixed $middleware
     * @return void
     */
    public function contrller($middleware)
    {
        $this->register($middleware, 'controller');
    }

    /**
     * 获取中间件
     *
     * @param string $type
     * @return void
     */
    public function all($type = 'route')
    {
        return $this->queue[$type] ?? [];
    }

    /**
     * 清除中间件
     *
     * @return void
     */
    public function clear(): void
    {
        $this->queue = [];
    }

    public function dispatch(Request $request, string $type = 'route')
    {
        return call_user_func($this->reslove($type), $request);
    }

    public function reslove(string $type = 'route')
    {
        return function(Request $request) use ($type) {
            $middleware = array_shift($this->queue[$type]);
            list($call, $params) = $middleware;
            
            return call_user_func_array($call, [$request, $this->reslove($type), $params]);  
        };
    }

}
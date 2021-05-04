<?php
namespace zero;

use Closure;

class Middleware
{
    protected $queue = [];

    /**
     * @param $key 
     * @param $type string
     */
    public function register($key, string $type = 'route') :bool
    {
        if( is_null($key) ){
            return false;
        }
        if( $key instanceof Closure ){
            $object = $key;
        }
        $this->requeue[$type][] = $object;
        return true;
    }

    public function handle($params = [], $type = 'route')
    {
        $element = array_shift($this->requeue[$type]);
        return call_user_func_array($element, $params);
    }
}
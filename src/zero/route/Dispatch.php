<?php
namespace zero\route;

use zero\Request;
use zero\Container;
use zero\Response;

abstract class Dispatch
{
    /**
     * objects 
     */
    protected $app;
    protected $request;
    protected $rule;

    /**
     * whether case-sensitive
     */
    protected $convery;

    protected $dispatch;

    protected $param;

    public function __construct(Request $request, Rule $rule, $dispatch = null, $param = null, $code = null)
    {
        $this->request = $request;
        $this->rule = $rule;
        $this->dispatch = $dispatch;
        $this->param = $param;
        $this->app = Container::get('application'); 
    }

    abstract public function exec();

    public function run()
    {
        $data = $this->exec();
        return $this->autoResponse($data);
    }

    public function autoResponse($data)
    {
        if( $data instanceof Response ) {
            $response = $data;
        } else {
            $isAjax = $this->request->isAjax();
            $type = $isAjax ? $this->rule->router->config['default_ajax_return_type'] : $this->rule->router->config['default_return_type']; 
            $response = new Response($data, $type);
        }

        return $response; 
    }

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['app'], $data['request'], $data['rule']);

        return $data;
    }
}
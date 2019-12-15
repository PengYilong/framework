<?php
namespace zero;

class Request
{

    /**
     * @var array $_SERVER object 
     */
    public $server = [];

    /**
     * 
     * @var string current module
     */
    public $module;

    /**
     * 
     * @var string current controller
     */
    public $controller;

    /**
     * 
     * @var string current action
     */
    public $action;

    /**
     * @var array route variables
     */
    public $route = [];

    public function __construct(Application $app, Config $config)
    {
        $this->server = $_SERVER;
    }

    public function pathinfo()
    {
        return $this->server['PATH_INFO']; 
    }

    /**
     * @return bool
     */
    public function isMethod(string $method) : bool
    {
        return $this->method() == $method;
    }

    /**
     * gets request method 
     */
    public function method()
    {
        return $this->server['REQUEST_METHOD'] ?: 'GET'; 
    }

    /**
     * e.g.  getRootDomain(getapi.zero.own) = zero.own
     */
    public function getRootDomain() :string
    {
        $root = $this->server['HTTP_HOST'];
        $array = explode('.', $root);
        $num = count($array);
        return $num>1 ? $array[$num-2] . '.' . $array[$num-1] : $array[0];
    }
    
    public function isAjax()
    {
        $result = false;
        if( !empty($this->server['HTTP_X_REQUESTED_WITH']) && $this->server['HTTP_X_REQUESTED_WITH'] == 'xmlhttprequest' ){
            $result = true;
        }
        return $result;
    }

    public function setRouteVars(array $route)
    {
        $this->route = array_merge($this->route, $route);
        return $this;
    }
}
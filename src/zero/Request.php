<?php
namespace zero;

class Request
{
    /**
     * 请求类型
     * @var string
     */
    protected $method;

    /**
     * current URL
     *
     * @var string
     */
    protected $url;

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

    protected $pathinfo;

    public function __construct(Application $app, Config $config)
    {
        $this->server = $_SERVER;
    }

    public function pathinfo()
    {
        $pathinfo = $this->server['PATH_INFO']; 
        $this->pathinfo = empty($pathinfo) || '/' == $pathinfo ? '' : ltrim($pathinfo, '/');
        return $this->pathinfo; 
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
     *
     * @param boolean $origin
     * @return void
     */
    public function method($origin = false)
    {
        if($origin){
            return $this->server['REQUEST_METHOD'] ?: 'GET'; 
        } elseif(!$this->method) {
            return $this->server['REQUEST_METHOD'] ?: 'GET'; 
        }
        return $this->method;
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
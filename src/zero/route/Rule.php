<?php
namespace zero\route;

use zero\Request;
use zero\route\dispatch\Module as ModuleDispatch;

abstract class Rule
{
    /**
     * 路由标识
     * @var string  
     */
    protected $name;

    /**
     * 路由对象
     * @var Route
     */
    public $router;

    /**
     * 路由所属分组
     * @var string  
     */
    protected $parent;

    /**
     * 路由规则
     * @var string  
     */
    protected $rule;

    /**
     * 路由地址
     * @var string|\Closure  
     */
    protected $route;

    /**
     * 请求类型
     * @var string
     */
    protected $method;

    /**
     * 路由变量
     * @var array 
     */
    protected $vars = [];

    /**
     * 路由参数
     * @var array
     */
    protected $option = [];

    /**
     * 变量规则
     * @var string  
     */
    protected $pattern = [];

    /**
     * 是否锁定参数
     *
     * @var boolean
     */
    protected $lockOption = false;

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $rule
     * @param [type] $route
     * @param [type] $url
     * @param array $options
     * @param array $matches
     * @return void
     */
    public function parseRule(Request $request, $rule, $route, $url, array $option = [], array $matches = [])
    {
        return $this->dispatch($request, $route, $option);
    }

    public function dispatch(Request $request, $route, array $option)
    {
        $result = $this->dispatchModule($request, $route);
        return $result;
    }

    public function dispatchModule(Request $request, string $route)
    {
        list($path, $var) = $this->parseUrlPath($route);

        $action = array_pop($path);
        $controller = !empty($path) ? array_pop($path) : NULL;
        $module = $this->router->config['app_multi_module'] && !empty($path) ? array_pop($path) : NULL;

        $dispatch = [$module, $controller, $action];
        $param = ['convert' => false];
        return new ModuleDispatch($request, $this, $dispatch, $param);
    }

    /**
     * 解析pathinfo url参数和变量
     *
     * @param string $url URL
     * @return array
     */
    public function parseUrlPath(string $url) : array
    {
        // 分隔符替换 确保路由定义统一的分隔符
        $url = str_replace('|', '/', $url);
        $url = trim($url, '/');
        $var = [];

        if( false !== strpos($url, '?') ){
            // [模块/控制器/操作?]参数1=值1&参数2=值2....
            $info = parse_url($url);
            $path = explode('/', $info['path']);
            parse_str($info['query'], $var);
        } elseif( strpos($url, '/') ){
            // [模块/控制器/操作]
            $path = explode('/', $url);
        } elseif( false !== strpos($url, '=') ) {
            //参数1=值1&参数2=值2...
            $path = [];
            parse_str($url, $var);
        } else {
            $path = [$url];
        }

        return [$path, $var];
    }

    public function mergeGroupOptions()
    {
        if(!$this->lockOption) {

        }

        return $this->option;
    }

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['router'], $data['parent']);

        return $data;
    }
}
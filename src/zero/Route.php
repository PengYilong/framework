<?php
namespace zero;

use zero\Config;
use zero\Factory;
use zero\URL;
use zero\exceptions\HttpException;
use zero\exceptions\RouteNotFoundException;
use zero\route\Domain;
use zero\route\dispatch\Url as UrlDispatch;
use zero\route\RuleGroup;

class Route
{

    /**
     * @var Application
     */
    protected $app;

    /**
     * request object
     * @var Request
     */
    protected $request;

    /**
     * current HOST
     *
     * @var string
     */
    protected $host;

    /**
     * current domain
     * @var string
     */
    protected $domain;

    /**
     * 当前分组对象
     * @var RuleGroup
     */
    public $group;

    /**
     * @var array
     */
    public $config = [];

    /**
     * 路由绑定
     * @var array
     */
    protected $bind = [];

    /**
     * @var array 
     */
    protected $domains = [];

    /**
     * 路由别名
     * @var array 
     */
    protected $alias = [];

    /**
     * 路由延迟解析
     * @var bool
     */
    protected $lazy = true;

    /**
     * wheteher auto search controller
     */
    protected $autoSearchController = true;

    public function __construct(Application $app, Config $config)
    {
        $this->app = $app;
        $this->request = $app['request'];
        $this->config = $config->get('app.');

        $this->host = $this->request->server['HTTP_HOST'];
        $this->lazy = $config->get('url_lazy_route');
        $this->autoSearchController = $config->get('controller_auto_search'); 
        
        $this->setDefaultDomain();
    }

    public function filterParam()
    {
        if(!get_magic_quotes_gpc()) {
            $_POST = new_addslashes($_POST);
            $_GET = new_addslashes($_GET);
            $_REQUEST = new_addslashes($_REQUEST);
            $_COOKIE = new_addslashes($_COOKIE);
        }

        return $this;
    }

    /**
     * 初始化默认域名
     *
     * @return void
     */
    protected function setDefaultDomain(): void
    {
        // 默认域名
        $this->domain = $this->host;

        // 注册默认域名
        $domain = new Domain($this, $this->host);

        $this->domains[$this->host] = $domain;

        //default group
        $this->group = $domain;
    }

    /**
     * @param string|array $name
     * @param string $rule
     * @return object Domain
     */
    /**
     * 注册域名路由
     *
     * @param string|array $name
     * @param string $rule
     * @param array $options
     * @param array $pattern
     * @return void
     */
    public function domain($name, $rule = '', array $options = [], array $pattern = [])
    {
        $domainName = is_array($name) ? array_shift($name) : $name;

        if( '*' != $name || !strpos('.', $name) ){
            $domainName = $name. '.' . $this->request->getRootDomain();
        }

        if( !isset($this->domian[$domainName]) ){
            $domain = (new Domain($this, $domainName, $rule, $options, $pattern))
                ->lazy($this->lazy);

            $this->domains[$domainName] = $domain;
        } else {
            $domain = $this->domains[$domainName];
            $domain->parseGroupRule($rule);
        }

        if( is_array($name) && !empty($mame) ){
            $root = $this->request->rootDomain();
            foreach($name as $item){
                if(false === strpos($item, '.')) {
                    $item .= '.' . $root;
                }

                $this->domains[$item] = $omainName;
            }
        } 

        return $domain;
    }

    public function bind(string $domain = null, string $bind): void
    {
        $domain = is_null($domain) ? $this->domain : $domain;
        $this->bind[$bind] = $domain;
    }

    /**
     * gets domain bind
     */
    public function getBind($domain = null)
    {
        if( is_null($domain) ){
            $domain = $this->host;
        }

        if( isset($this->bind[$domain]) ) {
            $result = $this->bind[$domain];
        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @param boolean $must 强制路由
     * @return void
     */
    public function check(string $url, bool $must = false)
    {
        //自动检测域名路由
        $domain = $this->checkDomain();
        $url = str_replace($this->config['pathinfo_depr'], '|', $url);

        $completeMatch = $this->config['route_complete_match'];
        
        //进行路由匹配
        $result = $domain->check($this->request, $url, $completeMatch);

        if( false !== $result ){
            return $result;
        } elseif( $must ){
            //开启强制使用路由，这种方式下面必须严格给每一个访问地址定义路由规则（包括首页），否则将抛出异常
            throw new RouteNotFoundException();
        }

        //默认路由解析
        return new UrlDispatch($this->request, $this->group, $url, [
            'auto_search' => $this->autoSearchController,
        ]);    
    }

    public function checkDomain()
    {
        $item = $this->domains[$this->host];
        return $item;
    }

    /**
     * register route
     * @access public
     * @param  mixed $rule    路由规则
     * @param  mixed  $route   路由地址
     * @param  string $method  请求方法
     * @param  array  $options 路由参数
     * @param  array  $pattern 变量规则
     * @return RuleItem
     */
    public function rule($rule, $route, string $method = '*', array $options = [], array $pattern = [])
    {
        return $this->group->addRule($rule, $route, $method, $options, $pattern);
    }

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['app'], $data['request']);

        return $data;
    }
}
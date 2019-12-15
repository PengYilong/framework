<?php
namespace zero;

use zero\Config;
use zero\Factory;
use zero\URL;
use zero\exceptions\HttpException;
use zero\route\Domain;
use zero\route\dispatch\Url as UrlDispatch;

class Route
{

    /**
     * @var array
     */
    public  $config;

    /**
     * @var string
     */
    public $url = NULL;

    /*
     * @var
     */
    protected $bindModule;

    /**
     * Application object
     */
    protected $app;

    /**
     * request object
     */
    public $request;

    /**
     * wheteher auto search controller
     */
    public $autoSearchController = true;

    /**
     * @var string
     */
    public $domain;

    /**
     * @var
     */
    public $domains;

    public $bind;

    /**
     * 路由延迟解析
     * @var bool
     */
    public $lazy = true;

    public function __construct(Application $app, Config $config)
    {
        $this->config = $config->get('app.');
        $this->request = $app['request'];
        $this->app = $app;
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

    public function setDefaultDomain()
    {
        $domain = new Domain($this, $this->host);
        $this->domains[$this->host] = $domain;
        $this->group = $domain;
    }

    /**
     * @param string|array $name
     * @param string $rule
     * @return object Domain
     */
    public function domain($name, $rule = '')
    {
        $rootDomain = $this->request->getRootDomain();
        if( is_array($name) ){
            foreach($name as $value){
                $this->domain($value, $rule);
            }
            return ;
        } 
        if( '*' != $name || !strpos('.', $name) ){
            $domainName = $name. '.' . $rootDomain;
        }
        if( !isset($this->domian[$domainName]) ){
            $domain = (new Domain($this, $domainName, $rule))->lazy($this->lazy);
            $this->domains[$domainName] = $domain;
        } else {
            $domain->parseGroupRule($rule);
        }
    }

    public function bind($bind, $domain = null)
    {
        $domain = is_null($domain) ? $this->domian : $domain;
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
        return $this->bind[$domain];
    }

    public function check($url)
    {
        return new UrlDispatch($this->request, $this->group, $url, [
            'auto_search' => $this->autoSearchController,
        ]);
    }
}
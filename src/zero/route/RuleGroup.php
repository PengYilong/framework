<?php
namespace zero\route;

use zero\Route;
use zero\Request;

class RuleGroup extends Rule
{

    //分组路由
    public $rules = [
        '*'      => [],
        'get'    => [],
        'post'   => [],
        'put'    => [],
        'patch'  => [],
        'delete' => [],
    ];

    /**
     * Miss路由
     *
     * @var [type]
     */
    protected $miss;

    /**
     * 自动路由
     *
     * @var [type]
     */
    protected $auto;

    /**
     * 完整名称
     *
     * @var [type]
     */
    protected $fullName;

    /**
     * 所在域名
     *
     * @var string
     */
    protected $domain;

    /**
     * 
     */
    public function __construct(Route $router, RuleGroup $parent = null)
    {
        $this->router = $router;
        $this->parent = $parent;
    }

    public function parseGroupRule($rule)
    {
        $this->router->bind($rule, $this->domain);
    }
   
    public function lazy($lazy = true)
    {
        if(!$lazy){
            $this->parseGroupRule($this->rule);
        }
        return $this;
    }

    /**
     * 
     * @access public
     * @param  mixed  $rule    路由规则
     * @param  mixed  $route   路由地址
     * @param  string $method  请求方法
     * @param  array  $options 路由参数
     * @param  array  $pattern 变量规则
     * @return 
     */
    public function addRule($rule, $route, string $method = '*', array $options, array $pattern)
    {
        $name = $route;
        $method = strtolower($method);
        $ruleItem = new RuleItem($this->router, $this, $name, $rule, $route, $method, $options, $pattern);

        $this->addRuleItem($ruleItem, $method);

        return $ruleItem;
    }

    /**
     * add ruleitem
     *
     * @param [type] $ruleItem
     * @param string $method
     * @return void
     */
    public function addRuleItem($ruleItem, $method = '*')
    {
        $this->rules[$method][] = $ruleItem;   
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param string $url
     * @param boolean $completeMatch
     * @return void
     */
    public function check(Request $request, string $url, bool $completeMatch = false)
    {
        // 获取当前路由规则
        $method = strtolower($request->method());
        $rules = $this->getMethodRules($method);

        foreach($rules as $key => $item) {
            $result = $item->check($request, $url, $completeMatch);
            
            if( false !== $result ) {
                return $result;
            }
        }
    }

    /**
     * 获取分组的路由规则
     */
    public function getRules(string $method = ''): array
    {
        if( '' == $method ){
            return $this->rules;
        }
        return $this->rules[strtolower($method)] ?? [];
    }

    protected function getMethodRules(string $method) : array
    {
        return array_merge($this->rules[$method], $this->rules['*']);
    }

    /**
     * 清空路由分组下的规则
     * @access public
     * @return void
     */
    public function clear(): void
    {
        $this->rules = [
            '*'      => [],
            'get'    => [],
            'post'   => [],
            'put'    => [],
            'patch'  => [],
            'delete' => [],
        ]; 
    }
}
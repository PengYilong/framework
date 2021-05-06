<?php
declare(strict_types = 1);

namespace zero\route;

use zero\Route;
use zero\Request;
use zero\Container;

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
     * @var string
     */
    public $fullName;

    /**
     * 所在域名
     *
     * @var string
     */
    protected $domain;

    /**
     * 分组前缀
     *
     * @var string
     */
    public $prefix;

    /**
     * constructor
     *
     * @param Route $router
     * @param RuleGroup $parent
     * @param string 分组名称
     * @param array|Clouse 分组路由
     */
    public function __construct(Route $router, RuleGroup $parent = null, string $name = '', $rule = null)
    {
        $this->router = $router;
        $this->parent = $parent;
        $this->name = $name;
        $this->rule = $rule;

        $this->setFullName();

        if( $this->parent ) {
            $this->parent->addRuleItem($this);
        }
    }

    public function setFullName()
    {
        if($this->parent && $this->parent->fullName) {
            $this->fullName = $this->parent->fullName . ($this->name ?  '/' . $this->name : '');
        } else {
            $this->fullName = $this->name;
        }
    }

    public function parseGroupRule($rule)
    {
        $this->router->setGroup($this);
        if( $rule instanceof \Closure ) {
           Container::getInstance()::invokeFunction($rule);
        } else {
            foreach($rule as $key => $value) {
                $this->addRule($key, $value);
            }
        }
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
     * @return 
     */
    public function addRule($rule, $route, string $method = '*'): RuleItem
    {
        $name = $route;
        $method = strtolower($method);
        
        $ruleItem = new RuleItem($this->router, $this, $name, $rule, $route, $method);

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
        if( $this instanceof Resource ) {
            $this->buildResourceRule();
        } elseif($this->rule) {
            $this->parseGroupRule($this->rule);
        }

        // 获取当前路由规则
        $method = strtolower($request->method());
        $rules = $this->getMethodRules($method);

        $completeMatch = $this->option['complete_match'] ?? $completeMatch;
        
        foreach($rules as $key => $item) {
            $result = $item->check($request, $url, $completeMatch);

            if( false !== $result ) {
                return $result;
            }
        }

        $result = false;

        return $result;
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

    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;
        return $this;
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
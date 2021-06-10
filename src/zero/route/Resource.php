<?php
namespace zero\route;

use zero\route;

/**
 * 资源路由
 */
class Resource extends RuleGroup
{
    public function __construct(Route $router, RuleGroup $parent = NULL, string $name = '', string $route = '', array $rest = [], $completeMatch = true)
    {
        $this->router = $router;
        $this->parent = $parent;
        $this->resource = $name;
        $this->name = $name;
        $this->route = $route;
        $this->rest = $rest;

        // 资源路由默认为完全匹配
        $this->option['complete_match'] = $completeMatch;

        $this->setFullName();
        
        if($this->parent) {
            $this->parent->addRuleItem($this);
        }
    }

    /**
     * build resourrce rules
     *
     * @return void
     */
    protected function buildResourceRule()
    {  
        $this->router->setGroup($this);
         
        $rule = $this->resource;

        if ( strpos($rule, '.') ) {
            
        }

        foreach ($this->rest as $key => $val) {
            $this->addRule($val[1], $this->route . '/' . $val[2], $val[0]);
        }
    }
}
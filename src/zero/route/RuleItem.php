<?php
namespace zero\route;

use zero\Route;
use zero\Request;
use zero\route\dispatch\Module as ModuleDispatch;
use Exception;

class RuleItem extends Rule
{
    /**
     * struct function
     *
     * @param Route $router
     * @param RuleGroup $parent
     * @param string $name   路由标识
     * @param [type] $rule   路由规则
     * @param [type] $route  路由地址
     * @param string $method 请求方法
     * 
     */
    public function __construct(Route $router, RuleGroup $parent, string $name, $rule, $route, string $method = '*')
    {
        $this->router = $router;
        $this->parent = $parent;
        $this->name = $name;
        $this->route = $route;
        $this->method = $method;

        $this->rule = $this->setRule($rule);
    }

    public function setRule(string $rule) : string
    {
        if($this->parent && $this->parent->fullName) {
            $rule = $this->parent->fullName . ($rule ? '/' . ltrim($rule, '/') : '');
        }
        
        if(false !== strpos($rule, ':') ) {
            $rule = preg_replace(['/\[\:(\w+)\]/', '/\:(\w+)/'], ['<\1?>', '<\1>'], $rule);
        } 

        return $rule;
    }


    public function check(Request $request, string $url, bool $completeMatch = false)
    {
        return $this->checkRule($request, $url, null, $completeMatch);
    }

    /**
     * 检查路由
     *
     * @param Request $request
     * @param string $url
     * @param [type] $match
     * @param boolean $completeMatch
     * @return void
     */
    public function checkRule(Request $request, string $url, $match = NULL, bool $completeMatch = false)
    {
        // 合并分组参数
        $option = $this->mergeGroupOptions();
        
        if( is_null($match) ){
            $match = $this->match($url, $option, $completeMatch);
        }
    
        if( false !== $match ){
            return $this->parseRule($request, $this->rule, $this->route, $url, $option, $match);
        }

        return false;
    }

    /**
     * 检测URL和规则路由是否匹配
     *
     * @param string $url
     * @param array $options
     * @param boolean $completeMatch
     * @return false|array
     */
    private function match(string $url, array $option, bool $completeMatch)
    {
        if( isset($option['complete_match']) ) {
            $completeMatch = $option['complete_match'];
        }

        $depr = $this->router->config['pathinfo_depr'];

        $var = [];
        $url = $depr . str_replace('|', $depr, $url);
        $rule = $depr . str_replace('/', $depr, $this->rule);
        
        $pattern = [];
        
        if( false === strpos($rule, '<') ) {
            if( 0 === strcasecmp($rule, $url) || (!$completeMatch && 0 === strncasecmp($rule . $depr, $url . $depr, strlen($rule . $depr) ) ) ) {
                return $var;
            } 
            return false;
        }

        $slash = preg_quote('/-' . $depr, '/');
        $regex = '/['. $slash .']?<\w+\??>/';
        if( $matchRule = preg_split($regex, $rule, 2) ) {
            if($matchRule[0] && 0 !== strncasecmp($rule, $url, strlen($matchRule[0])) ) {
                return false;
            }
        }
        
        if( preg_match_all('/['. $slash .']?<?\w+\??>?/', $rule, $matches) ) {
            $regex = $this->buildRuleRegex($rule, $matches[0], $pattern, $option, $completeMatch);
            try {
                $urlRegex = '/^'. $regex . ($completeMatch ? '$' : '') .'/u';
                
                if( !preg_match($urlRegex, $url, $match) ) {
                    return false;
                }
            } catch (Exception $e) {
                throw new Exception('route pattern error');
            }

            foreach($match as $key => $val) {
                if(is_string($key)) {
                    $var[$key] = $val;
                }
            }
        }

        return $var;
    }

    /**
     * 生成路由的正则规则
     *
     * @param [type] $rule
     * @param [type] $match
     * @param [type] $pattern
     * @param [type] $option
     * @param boolean $completeMatch
     * @return void
     */
    protected function buildRuleRegex($rule, $match, $pattern, $option, bool $completeMatch = false)
    {
        foreach($match as $name) {
            $replace[] = $this->buildNameRegex($name);
        }

        if( '/' != $rule) {
            if( substr($rule, -1) == '/' ) {
                $rule = rtrim($rule, '/');
            }
        }

        $regex = str_replace(array_unique($match), array_unique($replace), $rule);

        return $regex;
    }

    /**
     * 生成路由变量的正则规则
     *
     * @param string $name
     * @return string
     */
    protected function buildNameRegex(string $name) : string
    {
        $slash = substr($name, 0, 1);

        $prefix = '\\' . $slash;
        $name   = substr($name, 1);
        $slash  = substr($name, 0, 1);
    
        if( '<' != $slash ) {
            return $prefix . preg_quote($name, '/');
        }

        if( strpos($name, '>') ) {
            $name = substr($name, 1, -1);
        }

        $nameRule = $this->router->config['default_route_pattern'];

        return '(' . $prefix . '(?<' .$name . '>'. $nameRule .')' . ')';
    }

}
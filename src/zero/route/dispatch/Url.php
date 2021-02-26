<?php
namespace zero\route\dispatch;

use zero\route\Dispatch;
use zero\exception\HttpException;

class Url extends Dispatch
{
    public function init()
    {
        $result = $this->parseUrl($this->dispatch); 
        return (new Module($this->request, $this->rule, $result))->init();
    } 

    public function parseUrl($url)
    {
        $depr = $this->rule->router->config['pathinfo_depr'];
        $bind = $this->rule->router->getBind();

        if( !empty($bind) && preg_match('/^[a-z]/is', $bind) ){
            $url = $bind . (!empty($url) ? $depr . $url : ''); 
        }

        $url = ltrim($url, $depr);
        list($path, $var) = $this->rule->parseUrlPath($url);
        if( empty($path) ){
            return [NULL, NULL, NULL];
        }

        $module = $this->rule->router->config['app_multi_module'] ?  array_shift($path) : NULL;

        $find = true;
        if( empty($path) ){
            $controller = NULL;
        } else if( $this->param['auto_search'] ){
            list($controller, $find) = $this->autoFindController($module, $path);
        } else {
            $controller = array_shift($path);
        }

        if( !$find ){
            throw new HttpException(404, 'The controller doesn\'t exist : '. $controller);
        }
        
        $action = !empty($path) ? array_shift($path) : NULL;

        $var = [];

        //解析额外参数
        if( $path ){
            preg_replace_callback('/(\w+)\|([^\|]+)/', function($match) use (&$var) {
                $var[$match[1]] = strip_tags($match[2]);
            }, implode('|', $path));
        }

        $this->request->setRouteVars($var);

        $route = [$module, $controller, $action];
        return $route;
    }

    /**
     * automatically search controller
     * @access protected 
     * @param  string $module  module name
     * @param  array  $path    URL
     * @return string
     */
    protected function autoFindController($module, &$path)
    {
        $dir = $this->app->appPath . ($module ? $module.'/' : '') . $this->rule->router->config['url_controller_layer'];
        $item = [];
        $find = false;

        $num = count($path);

        foreach ($path as $key=>$value){
            $item[] = $value;
            $file = $dir . '/' .ucfirst($value);
            if( file_exists($file . '.php') ){
                $find = true;
                break;
            } else {
                //if the element is the end.
                if( $key == $num-1 ){
                    $value = ucfirst($value);
                }
                $dir .= '/'. $value; 
            }
        }

        if( $find ){
            $controller = implode('.', $item);
            $path = array_splice($path, count($item));
        } else {
            $controller = $dir;
        }

        return [$controller, $find];
    }

    public function exec()
    {}

}
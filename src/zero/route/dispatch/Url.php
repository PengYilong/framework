<?php
namespace zero\route\dispatch;

use zero\route\Dispatch;
use zero\exceptions\HttpException;

class Url extends Dispatch
{
    public function init()
    {
        $result = $this->parseUrl($this->dispatch); 
        return (new Module($this->request, $this->rule, $result))->init();
    } 

    public function parseUrl($url)
    {
        $bind = $this->rule->route->getBind();
        $depr = $this->rule->route->config['pathinfo_depr'];
        $url = ltrim($url, $depr);
        if( !empty($bind) && preg_match('/^[a-z]/is', $bind) ){
            $url = $bind . (!empty($url) ? $depr . $url : ''); 
        }
        $path = explode($depr, $url);
        $var = [];
        if( empty($path) ){
            return [NULL, NULL, NULL];
        }
        $module = $this->rule->route->config['app_multi_module'] ?  array_shift($path) : NULL;

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

        //gets params
        if( !empty($path) ){
            if( $this->rule->route->config['url_param_type'] ){
                $var += $path;
            } else {
                for($i=0; $i<count($path); $i+=2){
                    if(isset($path[$i+1])){
                        $var[$path[$i]] = $path[$i+1];
                    }
                }
            }
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
        $dir = $this->app->appPath . ($module ? $module.'/' : '') . $this->rule->route->config['url_controller_layer'];
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
<?php
namespace zero\route\dispatch;

use zero\route\Dispatch;
use zero\exception\HttpException;
use zero\exception\ClassNotFoundException;

class Module extends Dispatch
{
    protected $controller;
    protected $actionName;

    public function init()
    {
        parent::init();
        
        $result = $this->dispatch;
        
        if( is_string($result) ) {
            $result = explode('/', $result);
        }
        
        $module = $result[0] ?: $this->rule->router->config['default_module'];
        
        if( $this->rule->router->config['app_multi_module'] ){
            if( is_dir($this->app->appPath.$module) ){
                $this->request->module = $module;
                $this->app->init($module);
            } else {
                throw new HttpException(404, 'Module not exist : '. $module);
            }
        }
        
        $this->controller = $result[1] ?: $this->rule->router->config['default_controller'];
        $this->actionName = $result[2] ?: $this->rule->router->config['default_action'];

        $this->request->controller = ucfirst($this->controller);
        $this->request->action = $this->actionName;
        return $this;
    }    

    public function exec()
    {
        try {
            $instance = $this->app->controller($this->controller, $this->rule->router->config['url_controller_layer']);
        } catch(classNotFoundException $e ){
            throw new HttpException(404, 'controller not exists '. $e->class);
        }
        $this->app->middleware->register(
            function () use ($instance){
                $action = $this->actionName;
                if( is_callable([$instance, $action]) ){
                    $vars = $this->request->route;
                    $reflectMethod = new \ReflectionMethod($instance, $action);
                    return $this->app->invokeReflectMoethod($instance, $reflectMethod, $vars);
                    // return call_user_func_array([$instance, $action], $vars);
                } else {
                    throw new HttpException(404, 'method not exists :'. get_class($instance). '->'. $action ); 
                } 
            }
            , 'controller');
        return $this->app->middleware->dispatch($this->request, 'controller');
    }

}
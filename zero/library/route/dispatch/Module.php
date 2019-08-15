<?php
namespace zero\route\dispatch;

use zero\route\Dispatch;
use zero\exceptions\HttpException;
use zero\exceptions\ClassNotFoundException;

class Module extends Dispatch
{

    protected $controller;
    protected $actionName;

    public function init()
    {
        $result = $this->dispatch;

        if( $this->rule->route->config['app_multi_module'] ){
            $module = $result[0] ?: $this->rule->route->config['default_module'];
            if( is_dir($this->app->appPath.$module) ){
                $this->request->module = $module;
                $this->app->init($module);
            } else {
                throw new HttpException(404, 'Module not exist : '. $module);
            }
        }
        $this->controller = $result[1] ?: $this->rule->route->config['default_controller'];
        $this->actionName = $result[2] ?: $this->rule->route->config['default_action'];

        $this->request->controller = ucfirst($this->controller);
        $this->request->action = $this->actionName;
        return $this;

        /*
        //get new $class
        $classArr = [
            'app',
            $result[0],
            $this->config['app']['url_controller_layer'],
            ucfirst($result[1]),
        ];
        if( !empty($this->directory) ){
            $classArr = array_insert($classArr, 3, $this->directory);
        }
        $class = '\\'.implode('\\',$classArr);
        new Factory($result[0], $this->directory, $result[1], $result[2]);

        //Add decorator
        $decorators = $this->config['decorators']['output_decorators'];
        $dec_obj = [];
        //gets global object of  decorators
        if( isset($_GET['app']) && !empty($decorators) ){
            foreach ($decorators as $key => $value) {
                $dec_obj[] = new $value;
            }
            foreach ($dec_obj as $key => $value) {
                $value->beforeRequest();
            }
        }
        $object = new $class($result[0], $this->directory, $result[1], $result[2]);

        $method = $this->action;
        $result = $object->$method();
        if( isset($_GET['app']) && !empty($dec_obj)){
            foreach ($dec_obj as $key => $value) {
                $value->afterRequest($result, $object);
            }
        }
        */
    }    

    public function exec()
    {
        try {
            $instance = $this->app->controller($this->controller, $this->rule->route->config['url_controller_layer']);
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
        return $this->app->middleware->use([], 'controller');
    }

}
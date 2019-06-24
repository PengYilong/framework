<?php
namespace zero\route;

use zero\Route;
use zero\Config;
use zero\Factory;
use zero\URL;
class Origin extends Route
{

    public function __construct()
    {
        
    }

    public function init()
    {
        //gets default config of route
        $route_conf = Config::get('route')['default'];

        //gets module
        $this->module = !empty($_GET['m']) ? ucwords($_GET['m']) : $route_conf['module'];

        //gets controller
        $this->controller = !empty($_GET['c']) ? ucwords($_GET['c']) : $route_conf['controller'];

        //gets action
        $this->action =!empty($_GET['a'])  ? strtolower($_GET['a']) : $route_conf['action'];

        $this->controller_low = strtolower($this->controller);

        $class = '\app\\'.$this->module.'\\controller\\'.$this->controller;
        

        new Factory($this->module, $this->controller, $this->action); 
        //Add decorator
        $decorators = [];
        $decorators_conf = Config::get('decorators');
        $decorators = $decorators_conf['output_decorators'];
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
        
        $object = new $class($this->module, $this->controller, $this->action);

        $method = $this->action;
        $result = $object->$method();

        if( isset($_GET['app']) && !empty($dec_obj)){
            foreach ($dec_obj as $key => $value) {
                $value->afterRequest($result, $object);
            }
        }
        
    }
}
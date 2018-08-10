<?php
namespace Zero\library\route;

use Zero\library\Route;
use Zero\library\Config;
use Zero\library\Factory;
use Zero\library\URL;
class Compatibility extends Route
{

    public function __construct()
    {

    }

    public function init()
    {

        $url = $_SERVER['PATH_INFO'];
        if(  $url !== NULL ){
            //gets default config of route
            $route_conf = Config::get('route')['default'];
            $url = trim($url, '/'); //去掉左右两边的/
            $url_array = explode('/', $url);

            //gets module
            $this->module = !empty($url_array[0]) ? ucwords(array_shift($url_array)) : $route_conf['module'];

            //gets controller
            $this->controller = !empty($url_array[0])  ? ucwords(array_shift($url_array)) : $route_conf['controller'];

            //gets action
            $this->action = !empty($url_array[0])  ? strtolower(array_shift($url_array)) : $route_conf['action'];

            $this->controller_low = strtolower($this->controller);

            //gets params after action
            if( !empty($url_array) ){
                for($i=0; $i<count($url_array); $i+=2){
                    if(isset($url_array[$i+1])){
                        $_GET[$url_array[$i]] = $url_array[$i+1];
                    }
                }
            }

            $class = '\App\\'.$this->module.'\\Controller\\'.$this->controller;
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
}
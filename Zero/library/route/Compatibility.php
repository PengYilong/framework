<?php
namespace Zero\library\route;

use Zero\library\Route;
use Zero\library\Config;
use Zero\library\Factory;
use Zero\library\URL;

class Compatibility
{
    /**
     * @var array
     */
    protected  $config;

    /*
     * @var
     */
    protected $bindModule;

    /**
     * @var string
     */
    public $module;

    /**
     * @var string
     */
    public $directory = [];

    /**
     * @var string
     */
    public $controller;

    /**
     * @var string
     */
    public $action;

    public function __construct($config = [])
    {
        $this->config = $config;
    }

    public function init()
    {
        $url = $_SERVER['PATH_INFO'];
        if( $url !== NULL ){
            //$this->parseUrl($url);

            $domainArr = explode('.', $_SERVER['HTTP_HOST']);
            $currentModule = array_shift($domainArr);
            //比对绑定的模块
            if( in_array($currentModule, array_keys($this->config['bind_modules'])) ){
                $this->bindModule = $currentModule;
            }
            $this->module = strtolower($this->config['bind_modules'][$currentModule]);
            //自动查找文件
            //去除两边的/防止生成多余的数组元素
            $url = trim($url, '/');
            $path = explode('/', $url);
            $file = APP_PATH.ucfirst($this->module).'/'.$this->config['url_controller_layer'];

            foreach ($path as $key=>$value){
                $file .= '/'.ucfirst($value);
                if( file_exists($file.EXT) ){
                    $this->controller = strtolower(array_shift($path));
                    break;
                }
                array_push($this->directory, array_shift($path));
            }
            $this->action = strtolower(array_shift($path));

            //get new $class
            $classArr = [
                'App',
                $this->module,
                $this->config['url_controller_layer'],
                $this->controller,
            ];
            if( !empty($this->directory) ){
                $classArr = arrayInsert($classArr, 3, $this->directory);
            }
            $classArr = array_map("ucfirst", $classArr);
            $class = '\\'.implode('\\',$classArr);

            new Factory($this->module, $this->directory, $this->controller, $this->action);

            //gets params after action
            if( !empty($path) ){
                for($i=0; $i<count($path); $i+=2){
                    if(isset($path[$i+1])){
                        $_GET[$path[$i]] = $path[$i+1];
                    }
                }
            }
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
            $object = new $class($this->module, $this->directory, $this->controller, $this->action);
            $method = $this->action;
            $result = $object->$method();
            if( isset($_GET['app']) && !empty($dec_obj)){
                foreach ($dec_obj as $key => $value) {
                    $value->afterRequest($result, $object);
                }
            }
        }
    }

    /**
     *
     *
     */
    public function parseUrl($url)
    {
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
    }
}
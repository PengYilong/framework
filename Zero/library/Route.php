<?php
namespace Zero\library;

class Route
{
	public function __construct()
	{

        if(!get_magic_quotes_gpc()) {
            $_POST = new_addslashes($_POST);
            $_GET = new_addslashes($_GET);
            $_REQUEST = new_addslashes($_REQUEST);
            $_COOKIE = new_addslashes($_COOKIE);
        }
        $this->init();
    }

    private function init()
    {
        $url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        //获取默认route配置
        $route_conf = Config::get('route');
        if( isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']!='/' ){
            $url = trim($url, '/'); //去掉左右两边的/
            $url_array = explode('/', $url);

            //获取模块
            $module = !empty($url_array[0])  ? ucwords(array_shift($url_array)) : $route_conf['module'];
            // define('ROUTE_M', $module);

            //获取控制器
            $controller = !empty($url_array[0])  ? ucwords(array_shift($url_array)) : $route_conf['controller'];
            // define('ROUTE_C', $controller);

            //获取方法
            $action = !empty($url_array[0])  ? strtolower(array_shift($url_array)) : $route_conf['action'];
            // define('ROUTE_A', $action);

            $controller_low = strtolower($controller);

            //获取后面的参数
            if( !empty($url_array) ){
                for($i=0; $i<count($url_array); $i+=2){
                    if(isset($url_array[$i+1])){
                        $_GET[$url_array[$i]] = $url_array[$i+1];
                    }
                }
            }

            $class = '\App\\'.$module.'\\Controller\\'.$controller;
            $object = new $class($module, $controller, $action);
            //将mca参数传递给Factory
            new Factory($module, $controller, $action);

            $object->$action($module, $controller, $action);

        } 
    }

}
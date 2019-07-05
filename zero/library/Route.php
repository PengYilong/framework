<?php
namespace zero;

use zero\Config;
use zero\Factory;
use zero\URL;
use zero\exceptions\HttpException;

class Route
{

    /**
     * @var array
     */
    protected  $config;

    /**
     * @var string
     */
    public $url = NULL;

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

    /**
     * Application object
     */
    protected $app;

    /**
     * request object
     */
    protected $request;

    /**
     * wheteher auto search controller
     */
    public $autoSearchController = true;

    public function __construct(Application $app, Config $config)
    {
        $this->config = $config->get();
        $this->request = $app['request'];
        $this->app = $app;
    }
  
    public function filterParam()
    {
        if(!get_magic_quotes_gpc()) {
            $_POST = new_addslashes($_POST);
            $_GET = new_addslashes($_GET);
            $_REQUEST = new_addslashes($_REQUEST);
            $_COOKIE = new_addslashes($_COOKIE);
        }
        return $this;
    }
  
    public function init()
    {
        $url = $this->request->pathinfo();
        if( $url !== NULL ){
            $domainArr = explode('.', $_SERVER['HTTP_HOST']);
            $currentModule = array_shift($domainArr);
            //比对绑定的模块
            if( in_array($currentModule, array_keys($this->config['app']['bind_modules'])) ){
                $this->bindModule = $currentModule;
            }
            $this->module = strtolower($this->config['app']['bind_modules'][$currentModule]);
            
            //去除两边的/防止生成多余的数组元素
            $url = trim($url, '/');
            $path = explode('/', $url);

            $this->autoFindController($path);
            
            if( !$this->controller ){
                throw new HttpException('The controller doesn\'t exist:'. $this->controller);
            }
            
            $this->action = !empty($path) ? strtolower(array_shift($path)) : NULL;

            //gets params after action
            if( !empty($path) ){
                for($i=0; $i<count($path); $i+=2){
                    if(isset($path[$i+1])){
                        $_GET[$path[$i]] = $path[$i+1];
                    }
                }
            }

            //get new $class
            $classArr = [
                'app',
                $this->module,
                $this->config['app']['url_controller_layer'],
                ucfirst($this->controller),
            ];
            if( !empty($this->directory) ){
                $classArr = array_insert($classArr, 3, $this->directory);
            }
            $class = '\\'.implode('\\',$classArr);
            new Factory($this->module, $this->directory, $this->controller, $this->action);

            //Add decorator
            $decorators = [];
            $decorators_conf = $this->config['decorators'];
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
     * @return string
     */
    public function autoFindController(&$path)
    {
        $file = $this->app->appPath . $this->module . '/' . $this->config['app']['url_controller_layer'];
        foreach ($path as $key => $value){
            $file .= '/'.ucfirst($value);
            if( file_exists($file . '.php') ){
                $this->controller = strtolower(array_shift($path));
                break;
            }
            array_push($this->directory, array_shift($path));
        }
    }

    public function getBind()
    {

    }
}
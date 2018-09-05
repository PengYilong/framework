<?php
namespace Zero\library;

use Zero\library\route\Compatibility;
use Zero\library\route\Origin;

class Route extends Application
{


    /**
     * @var array
     */
    protected  $config;

    /**
     * @var string
     */
    public $url = NULL;



    public function __construct($config = [])
    {
        $this->config = $config;
    }
  
    protected function chooseRoute()
    {
        switch ($this->config['url_model']) {
             case 1:
                $route = new Origin();     
                break;
             case 2:
                $route = new Compatibility($this->config);
                break; 
             default:
                $route = new Origin();   
         } 
         $route->init();
    }

    protected function filterParam()
    {
        if(!get_magic_quotes_gpc()) {
            $_POST = new_addslashes($_POST);
            $_GET = new_addslashes($_GET);
            $_REQUEST = new_addslashes($_REQUEST);
            $_COOKIE = new_addslashes($_COOKIE);
        }
        return $this;
    }
     
}
<?php
namespace Zero\library;

use Zero\library\route\Compatibility;
use Zero\library\route\Origin;
class Route
{

    /**
     * @var string
     */
    public $url = NULL;

    /**
     * @var string
     */
    public $module = NULL;

    /**
     * @var string
     */
    public $controller = NULL;

    /**
     * @var string
     */
    public $action = NULL;

     /**
     * @var int
     */
    public $urlModel;   


    public function __construct($urlModel)
    {
        if(!get_magic_quotes_gpc()) {
            $_POST = new_addslashes($_POST);
            $_GET = new_addslashes($_GET);
            $_REQUEST = new_addslashes($_REQUEST);
            $_COOKIE = new_addslashes($_COOKIE);
        }
        $this->urlModel = $urlModel;
        switch ($urlModel) {
             case 1:
                $route = new Origin();     
                break;
             case 2:
                $route = new Compatibility();
                break; 
             default:
                $route = new Origin();   
         } 
         $route->init();
    }
 
}
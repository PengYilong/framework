<?php
namespace Zero\library;

use Zero\driver\database\MySQLi;

class Factory
{

    static $module;
    static $controller;
    static $action;

	public function __construct($module, $controller, $action)
	{
		self::$module = $module;	
		self::$controller = $controller;
		self::$action = $action;	
	}	

	public static function getDatabase( $id = 'master' )
    {
        $key = 'database_'.$id;
        $database_config = Config::get('database');
        if( $id == 'master' ){
            $db_config = $database_config['master'];
        } else {
            $db_config = $database_config[array_rand($database_config['slave'])];
        }
        $db = Register::get($key);
        if( !$db ){
            $db = new MySQLi();
            $db->open($db_config);
            Register::set($key, $db);
        }
        return $db;
    }

    public static function getModel($name)
    {
       $key = 'app_model_'.$name;
       $model = Register::get($key);
       if(!$model){
            $class = 'App\\'.self::$module.'\\'.'Model\\'.$name;
            $model = new $class();
            Register::set($key, $model);
       }
       return $model;
       
    }
	
}
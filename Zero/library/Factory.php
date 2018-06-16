<?php
namespace Zero\library;

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

    public static function getModel($name)
    {
       $key = 'app_model_'.$name;
       $model = Register::get($key);
       if(!$model){
            $class = 'App\\'.self::$module.'\\'.'Model\\'.$name;
            if( self::$module ){
                $model = new $class();
                Register::set($key, $model);
            } else {
                throw new \Exception($model.' doesn\'t exist');
            }
            
       }
       return $model;
       
    }

    public static function getDatabase( $id = 'master' )
    {
        $key = 'database_'.$id;
        $database_config = Config::get('database');
        if( empty($database_config) ){
            return false;
        }
        if( $id == 'master' ){
            $db_config = $database_config['master'];
        } else {
            $db_config = $database_config[array_rand($database_config['slave'])];
        }
        $db = Register::get($key);
        if( !$db ){
            switch( $db_config['type'] ){
                case 'mysql':
                    $db = new \Nezumi\MySQL();
                    break;
                case 'mysqli':
                    $db = new \Nezumi\MySQLi();
                    break;
                case 'pdo':
                    $db = new \Nezumi\PDOMySql();
                    break;
                default:
                $db = new \Nezumi\MySQLi();
            }
            $db->open($db_config);
            Register::set($key, $db);
        }
        return $db;
    }


}
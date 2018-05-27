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
                    $db = new \mysql\MySQL();
                    break;
                case 'mysqli':
                    $db = new \mysql\MySQLi();
                    break;
                case 'pdo':
                    $db = new \mysql\PDOMySql();
                    break;
                default:
                $db = new \mysql\MySQLi();
            }
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

    public static function getCache()
    {
        $cache = new \cache\Memcached();
        $cache_conf = Config::get('cache');
        $cache->open($cache_conf);
        return $cache;
    } 
    
}
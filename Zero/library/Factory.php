<?php
namespace Zero\library;

class Factory
{

    static $module;
    static $controller;
    static $action;

    public function __construct($module, $directory, $controller, $action)
    {
        self::$module = $module;
        self::$controller = $directory;
        self::$controller = $controller;
        self::$action = $action;    
    }   

    public static function getModel($name, $type = 0)
    {
        $typeName = $type ==0 ? 'model' : 'business';
        $key = 'app_'.$typeName.'_'.$name;
        $model = Register::get($key);
        if(!$model){
            $class = 'App\\'.self::$module.'\\'.ucwords($typeName).'\\'.$name;
            if( self::$module ){
                $model = new $class();
                Register::set($key, $model);
            } else {
                throw new \Exception($model.' doesn\'t exist');
            }
        }
        return $model;
    }

}
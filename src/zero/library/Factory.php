<?php
namespace zero;

class Factory
{

    static $module;
    static $directory;
    static $controller;
    static $action;

    public function __construct($module, $directory, $controller, $action)
    {
        self::$module = $module;
        self::$directory = $directory;
        self::$controller = $controller;
        self::$action = $action;    
    }

    /**
     * @param $name
     * @param int $type
     * @param array $haveDirectory
     * @return bool
     * @throws \Exception
     */
    public static function getModel($name, $type = 0, $haveDirectory = [])
    {
        $typeName = $type ==0 ? 'model' : 'business';
        $key = 'app_'.$typeName.'_'.$name;
        $model = Register::get($key);
        if(!$model){
            $classArr = [
                'app',
                'common',
                $typeName,
                $name,
            ];
            if( !empty($haveDirectory) ){
                $classArr = array_insert($classArr, 3, $haveDirectory);
            }
            $class = '\\'.implode('\\', $classArr);
            $model = new $class();
            Register::set($key, $model);
        }
        return $model;
    }

}
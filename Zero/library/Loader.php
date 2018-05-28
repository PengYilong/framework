<?php
namespace Zero\library;

class Loader
{
	static $classMap = array();  //to load class

	static function _autoload($class)
	{
		if(isset(self::$classMap[$class])){
			return true;
		}
		$file = ROOT_PATH.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
		if( file_exists($file) ){
			include $file;
			self::$classMap[$class] = $class;
		} else {
			return false;
		}
	}

}
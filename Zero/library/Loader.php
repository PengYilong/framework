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
		$judge_namespace = explode('\\', $class);
		$first = array_shift($judge_namespace);
		if( $first == 'Zero' ){
			$file = ZERO_PATH.implode(DIRECTORY_SEPARATOR, $judge_namespace).'.php';
		} else if( $first == 'App' ){
			$file = APP_PATH.implode(DIRECTORY_SEPARATOR, $judge_namespace).'.php';
		} else {
			$file = ROOT_PATH.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
		}
		
		if( file_exists($file) ){
			include $file;
			self::$classMap[$class] = $class;
		} else {
            header('HTTP/1.1 404 Not Found');
			throw new \Exception($file.' doesn\'t exist');
		}
	}

}
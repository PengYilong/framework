<?php
namespace Zero\library;

class Config
{

	static $path = array();
	static $configs = array(); //所有配置
	static $extension = '';

	public function __construct($path, $extension)
	{
		self::$path = $path;
		self::$extension = $extension;
	}	

	/**
	 * get config of file
	 * @var type
	 */	
	static function get( $offset )
	{
		if(empty(self::$configs[$offset])){
			//loading frontend and backend config.finally result is fronted config.
			$config = array();
			foreach (self::$path as $key => $value) {
				$file = $value.$offset.self::$extension;
				if( file_exists($file) ){
					$config = include $file;
				}
			}
			self::$configs[$offset] = $config;
		}
		return self::$configs[$offset];
	}

}
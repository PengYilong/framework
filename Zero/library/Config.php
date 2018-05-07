<?php
namespace Zero\library;

class Config
{

	static $path = NULL;
	static $configs = array(); //所有配置
	static $extension = '';

	public function __construct($path, $extension)
	{
		self::$path = $path;
		self::$extension = $extension;
	}	

	/**
	 * 获取数组
	 * @var type
	 */	
	static function get( $offset )
	{
		if(empty(self::$configs[$offset])){
			$file = self::$path.$offset.self::$extension;
			if( file_exists($file) ){
				$config = include $file;
				self::$configs[$offset] = $config;
			}
		}
		return self::$configs[$offset];
	}

}
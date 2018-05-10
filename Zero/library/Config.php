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
	 * 获取数组
	 * @var type
	 */	
	static function get( $offset )
	{
		if(empty(self::$configs[$offset])){
			//加载前台和后台的配置文件，以前台为准
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
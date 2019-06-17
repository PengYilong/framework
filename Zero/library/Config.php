<?php
namespace zero;

class Config
{

	static $path = [];
	static $configs = []; // all of configs
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
	public static function get( $offset )
	{
		if( empty(self::$configs[$offset]) ){
			//loading frontend and backend config.finally result is fronted config.
			$config = [];
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
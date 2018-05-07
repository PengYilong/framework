<?php
namespace Zero\library;

class Log
{
	/**
	 *  1.确定日志的存储方式
	 *  2.写日志
	 * 
	 */


	static  $example;

	public function __construct()
	{
		$write_mod = Config::get('log')['kind'];	
		$class = array_values($write_mod)[0].array_keys($write_mod)[0];
		self::$example = new $class;
	}


	static function write($message)
	{
		self::$example->write($message);
	}	

	 	
}
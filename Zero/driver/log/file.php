<?php
namespace Zero\driver\log;

use Zero\Library\Config;

class file
{

	public function __construct()
	{
	}
	
	public function write($message)
	{
		$path_string = ''; //目录
		//获取配置
		$path = Config::get('log')['path'];
		//弹出第一个
		$path_string .= array_shift($path);
		//全部格式化成date格式	
		array_walk($path, array($this, 'process'));
		//弹出最后一个得到文件名
		$file = array_pop($path);

		//得到目录
		$path_string .= implode('/', $path);
		
		if(!is_dir($path_string)){
			mkdir($path_string);
		}
		$file_extension = '.log';
		$file = $path_string.DIRECTORY_SEPARATOR.$file.$file_extension;
		$message = $this->format($message);
		// file_put_contents($file, $message, FILE_APPEND);
		$handle = fopen($file, 'a+');
		fwrite($handle, $message);
		fclose($handle);	
	}

	public function process(&$value)
	{
		$value = date($value);
		return $value;
	}

	private function format($message)
	{
		return date('Y-m-d H:i:s').':'.$message.PHP_EOL;
	} 


}
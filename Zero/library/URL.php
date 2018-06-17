<?php
namespace Zero\library;

class URL
{

	static $module;

	public function __construct($module)
	{	
		self::$module = strtolower($module);
	}

	public static function to( $url = [])
	{
		$app = Config::get('app');	
		switch ($app['url_model']) {
			case 1:
				$urlArr = explode('/', $url[0]);
				$url_str = '/index.php?m='.self::$module.'&c='.$urlArr[0].'&a='.$urlArr[1]; 
				break;
			case 2:
				$url_str = '/index.php?r='.self::$module.'/'.$url[0]; 	
				break;
			default:
				# code...
				break;
		}
		return $url_str;	
	}

}
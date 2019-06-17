<?php
namespace zero;

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
				$urlArr = explode('/', array_shift($url));
				$url_str = '/index.php?m='.self::$module.'&c='.strtolower($urlArr[0]).'&a='.$urlArr[1];
				foreach ($url as $key => $value) {
				 	$url_str .= '&'.$key.'='.$value;
				} 
				break;
			case 2:
				$url_str = '/'.self::$module.'/'.array_shift($url);
				foreach ($url as $key => $value) {
				 	$url_str .= '/'.$key.'/'.$value;
				} 	
				break;
			default:
				# code...
				break;
		}
		return $url_str;	
	}

}
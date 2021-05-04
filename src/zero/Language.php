<?php
namespace zero;

class Language
{

	static $module;
	static $controller;
	static $lang_setting;
	static $langs;

	public function __construct($module, $controller)
	{	
		self::$module = $module;
		self::$controller = strtolower($controller);
		self::$lang_setting = Config::get('app')['language'];
		$this->init();
	}

	public static function init()
	{
		$languages = [
			'system' => LANGUAGE_PATH.self::$lang_setting.'/system.lang.php',
			'app_syetem' => APP_PATH.self::$module.'/languages/'.self::$lang_setting.'/system.lang.php',
			'system_menu' => APP_PATH.self::$module.'/languages/'.self::$lang_setting.'/system_menu.lang.php',
			'controller' => APP_PATH.self::$module.'/languages/'.self::$lang_setting.'/'.self::$controller.'.lang.php',
		];
		$langs = [];
		$temp = [];
		foreach ($languages as $key => $value) {
			if( file_exists($value) && is_file($value) ){
				$temp = include_once($value);
				if( $temp === true ){ //loaded file
					continue;
				}	
				$langs = array_merge($langs, $temp);
				unset($temp);
			} 
		}
		self::$langs = $langs;
	}

	public static function to($key)
	{
		return isset(self::$langs[$key]) ? self::$langs[$key] : '';
	}
}
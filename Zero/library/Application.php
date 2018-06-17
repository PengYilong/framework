<?php
namespace Zero\library;
use Nezumi\MyError;

class Application
{

	/**
	 * @var  
	 */
	protected  $config;

	function __construct($config = [])
	{
		$this->config = $config;
	}

	public static function run()
	{
		$configs = array(
			CORE_CONF_PATH,
			CONF_PATH,	
		);
		//load configs and

		new Config($configs, CONF_EXT);

		//to init handling error and exception class
		$config = Config::get('log');

		$path = RUNTIME_PATH.'log'.DS;
		$rule = $config['rule'];
		$app = Config::get('app');
		new MyError($path, $rule, ZERO_PATH.'/template/error.php', $app['app_debug']);

		session_start();

		//route init
		new Route();		
	}

}
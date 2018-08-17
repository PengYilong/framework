<?php
namespace Zero\library;
use Nezumi\MyError;

class Application
{

	/**
	 * @var array 
	 */
	protected  $config;

	function __construct($config = [])
	{
		$this->config = $config;
	}

	public function run()
	{
        date_default_timezone_set($this->config['default_timezone']);
		$configs = array(
			CORE_CONF_PATH,
			CONF_PATH,	
		);
		//load configs and

		new Config($configs, CONF_EXT);

		//to init handling error and exception class
		$config = $this->config['conponents']['log'];
		$path = RUNTIME_PATH.'log'.DS;
		$rule = $config['rule'];

		new MyError($path, $rule, ZERO_PATH.'/template/error.php', $this->config['app_debug']);

		$route = new Route($this->config);
        $route->filterParam()->chooseRoute();
	}

}
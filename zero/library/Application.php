<?php
namespace zero;
use Nezumi\MyError;

class Application
{

	/**
	 * @var array 
	 */
	protected  $config;

	/**
	 * 
	 */
	public $appPath;

	function __construct($appPath = '')
	{
		$this->path($appPath);
	}

	public function run()
	{
		$this->config = require CONF_PATH.'app.php';;
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

		if( $this->config['enable_myerror'] ){
			new MyError($path, $rule, ZERO_PATH.'/template/error.php', $this->config['app_debug']);
		}
		
        session_start();
		$route = new Route($this->config);
        $route->filterParam()->chooseRoute();
	}

	public function init($a)
	{

	}

	public function path($appPath)
	{
		$this->appPath = $appPath ?: $this->getAppPath();
	}

	public function getAppPath()
	{
		if( is_null($this->appPath) ){
			$this->appPath = ClassLoader::getRootPath().'app';
		}
		return $this->appPath;
	}

}
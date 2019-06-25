<?php
namespace zero;
use Nezumi\MyError;

class Application extends Container
{

	/**
	 * 
	 */
	public $appPath;

	/**
	 * 
	 */
	public $zeroPath;
	public $rootPath;
	public $runtimePath;
	public $configPath;
	Public $configExt = '.php';

	function __construct($appPath = '')
	{
		$this->path($appPath);
		$this->zeroPath = dirname(__DIR__).DIRECTORY_SEPARATOR;
	}

	public function run()
	{
		$this->initialize();
		//to init handling error and exception class
		$config = $this->config->config;
		$path = $this->runtimePath.'log' . DIRECTORY_SEPARATOR;
		$rule = $config['log']['rule'];

		if( $config['app']['enable_myerror'] ){
			new MyError($path, $rule, $this->zeroPath.'/template/error.php', $config['app']['app_debug']);
		}
		
        session_start();
		$route = new Route($config);
        $route->filterParam()->chooseRoute();
	}

	public function initialize()
	{
		// date_default_timezone_set($this->config['default_timezone']);
		$this->rootPath = dirname($this->appPath).DIRECTORY_SEPARATOR;
		$this->runtimePath = $this->rootPath . 'runtime' . DIRECTORY_SEPARATOR;
		$this->configPath = $this->rootPath . 'config' . DIRECTORY_SEPARATOR;

		$this->config->set(require $this->zeroPath. 'convention.php');

		$this->init();
	}

	public function init($module = '')
	{
		$module = $module ? $module . DIRECTORY_SEPARATOR : '';
		$path = $this->appPath . $module;

		if( is_file($path. 'common.php') ){
			include_once $path. 'common.php';
		}
		
		//loads configs
		if( is_dir($path.'config') ){
			$dir = $path. 'config'. DIRECTORY_SEPARATOR;
		} else if( is_dir($this->configPath. $module) ){
			$dir = $this->configPath. $module; 
		}
		$files = isset($dir) ? scandir($dir) : [];

		if( !empty($files) ){
			foreach($files as $file){
				if($file != '.' && $file != '..'){
					$this->config->load($dir.$file, pathinfo($file, PATHINFO_FILENAME));	
				}
			}
		}
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
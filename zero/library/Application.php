<?php
namespace zero;
use Nezumi\MyError;

class Application extends Container
{

	/**
	 * 
	 */
	public $appPath;
	public $zeroPath;
	public $rootPath;
	public $runtimePath;
	public $configPath;
	Public $configExt;

	function __construct($appPath = '')
	{
		$this->path($appPath);
		$this->zeroPath = dirname(__DIR__).DIRECTORY_SEPARATOR;
	}

	public function run()
	{
		try{
			$this->initialize();

			$this->hook->use('app_init');

			$this->routeCheck();

        	$this->route->filterParam()->init();
			
			$this->middleware->register(function (){
			});

			$this->middleware->use();
			
		} catch(HttpResponseException $e) {
			p($e);
		}
		
	}

	public function initialize()
	{
		$this->rootPath = dirname($this->appPath).DIRECTORY_SEPARATOR;
		$this->runtimePath = $this->rootPath . 'runtime' . DIRECTORY_SEPARATOR;
		$this->configPath = $this->rootPath . 'config' . DIRECTORY_SEPARATOR;
		$this->routePath = $this->rootPath . 'route'. DIRECTORY_SEPARATOR; 

		$this->configExt = $this->env->get('config_ext', '.php');
		$this->config->set(require $this->zeroPath. 'convention.php');

		//to init handling error and exception class
		$path = $this->runtimePath.'log' . DIRECTORY_SEPARATOR;
		$rule = $this->config->get('log.rule');
		if( $this->config->get('app.enable_myerror') ) {
			new MyError($path, $rule);
		}
		
		$this->env->set([
			'zero_path' => $this->zeroPath,
			'root_path' => $this->rootPath,
			'runtime_path' => $this->runtimePath,
			'app_path' => $this->appPath,
			'runtime_path' => $this->runtimePath,
			'route_path' => $this->routePath,
			'config_path' => $this->configPath,
			'extend_path' => $this->rootPath. 'extend'. DIRECTORY_SEPARATOR,
			'vendor_path' => $this->rootPath. 'vendor'. DIRECTORY_SEPARATOR, 
		]);

		$this->env->set('app_namespace', 'app');
		$this->env->set('app_debug', $this->config->get('app.app_dubug'));
		
		classLoader::addNameSpace('app\\', $this->appPath);

		if( is_file($this->zeroPath.'helper.php') ){
			include $this->zeroPath.'helper.php';
		}

		$this->init();

		date_default_timezone_set($this->config->get('app.default_timezone'));

		$this->routeInit();
	}

	public function init($module = '')
	{
		$module = $module ? $module . DIRECTORY_SEPARATOR : '';
		$path = $this->appPath . $module;

		if( is_file($path. 'common.php') ){
			include_once $path. 'common.php';
		}

		if( is_file($path. 'tags.php') ){
			$this->hook->set(include $path. 'tags.php');
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
					$this->config->set(include $dir.$file, pathinfo($file, PATHINFO_FILENAME));	
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
			$this->appPath = ClassLoader::getRootPath() . 'app' . DIRECTORY_SEPARATOR;
		}
		return $this->appPath;
	}

	public function routeInit()
	{
		
	}

	public function routeCheck()
	{
		$path = $this->request->pathinfo();
	}

}
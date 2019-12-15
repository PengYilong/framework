<?php
namespace zero;
use zero\exceptions\ClassNotFoundException;

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
	public $configExt;
	public $routePath;

	function __construct(string $rootPath = '')
	{
		$this->zeroPath = __DIR__.DIRECTORY_SEPARATOR;
		$this->rootPath = $rootPath ? rtrim($rootPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : $this->getDefaultRootPath();
		$this->appPath = $this->rootPath . 'app' . DIRECTORY_SEPARATOR;;
		$this->runtimePath = $this->rootPath . 'runtime' . DIRECTORY_SEPARATOR;
		$this->configPath = $this->rootPath . 'config' . DIRECTORY_SEPARATOR;
		$this->routePath = $this->rootPath . 'route'. DIRECTORY_SEPARATOR;
	}

	public function run()
	{
		try{
			$this->initialize();

			$this->hook->use('app_init');

			$this->hook->use('app_dispatch');

			$dispatch = $this->routeCheck()->init();
			
			$this->hook->use('app_begin');
			$data = NULL;	
		} catch(HttpResponseException $exception) {
			$dispatch = NULL;
			$data = $exception->response;
		}
		
		$this->middleware->register(
			function(Request $request) use ($dispatch, $data){
				return is_null($data) ? $dispatch->run() : $data; 
			}
		);
		
		$response = $this->middleware->use([$this->request]);
		$this->hook->use('app_end', [$response]);
		return $response;
	}

	public function initialize()
	{
		$this->configExt = $this->env->get('config_ext', '.php');
		$this->config->set(require $this->zeroPath. 'convention.php');
		//to init handling error and exception class
		$path = $this->runtimePath.'log' . DIRECTORY_SEPARATOR;
		$rule = $this->config->get('log.rule');
		
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
			include $path. 'common.php';
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

	public function routeInit()
	{
		if( is_file($this->routePath. 'route.php') ){
			include $this->routePath. 'route.php';	
		}		
	}

	public function routeCheck()
	{
		$path = $this->request->pathinfo();
		return $this->route->filterParam()->check($path);
	}

	/**
	 * @access public
	 * @param $name  string the name of the class  
	 * @param $layer string 
	 * @return object
	 * @throws ClassNotFoundException 
	 */
	public function controller($name, $layer = 'controller')
	{
		$module = $this->request->module;
		$class = $this->parseClass($module, $layer, $name);
		if( class_exists($class) ){
			return parent::get($class, true);
		} 
		throw new ClassNotFoundException('class not exists '. $class, $class);
	}

	protected function getDefaultRootPath(): string
	{
		return dirname(dirname(dirname(dirname(dirname($this->zeroPath))))) . DIRECTORY_SEPARATOR;
	}

	/**
	 * @access public
	 * @param $module string module name
	 * @param $layer  string 
	 * @param $name   string the name of the class  
	 * @return object
	 * @throws ClassNotFoundException 
	 */
	public function parseClass($module, $layer, $name)
	{
		$name = str_replace('.', '\\', $name);
		$classArr = [
            $this->config->get('app.app_namespace'),
			$module,
			$layer,
			$name,
        ];
		$class = '\\'.implode('\\',$classArr);
		return $class;
	}

}
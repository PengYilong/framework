<?php
namespace zero;

use zero\facade\Config;
use Nezimi\Template;

class Controller
{
	/**
	 * object 
	 */
	protected $app;

	/**
	 * @var string
	 */
	protected $style = NULL;

	/**
	 * @var object
	 */ 
	protected $view = NULL;

    /**
     * @var array
     */
    protected $replace = NULL;

	/**
     * 视图输出字符串内容替换
	 * @var array
	 */ 
	protected $app_config = NULL;		

	public function __construct(Application $app = null)
	{
		$this->app = $app;
		$this->request = $app->request;
		
		$templateConfig =  $this->app->config->pull('template');
		// $this->app_config = $this->app->config->pull('app');
		// $this->style = $this->app_config['admin_style'];

		$templateDir = $this->app->appPath . $this->request->module . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . strtolower($this->request->controller) . DIRECTORY_SEPARATOR;
		$compieDir = $this->app->runtimePath . 'temp' . DIRECTORY_SEPARATOR . strtolower($this->request->controller) . DIRECTORY_SEPARATOR;

		$this->view = $this->initMySmarty($templateDir, $compieDir, $templateConfig);

		//视图输出字符串内容替换
        // if( !empty($this->app_config['view_replace_str']) ){
        //     foreach ($this->app_config['view_replace_str'] as $key=>$value){
        //         $this->replace[$key] = '/static/admin/'.$this->style.'/'.$value;
        //     }
        // }

        // new URL($this->module);
		// new Language($module, strtolower($controller));
		// $this->assign('languages', Language::$langs);
		// $this->assign('langs', json_encode(Language::$langs));
	}

    /**
     * init mysmarty
     *
     */
	protected function initMySmarty($templateDir, $compileDir, $config = [])
	{
		return new Template($templateDir, $compileDir, $config);
	}

	public function assign($key, $value)
	{
		$this->view->assign($key, $value);	
	}

	public function display($file = '')
	{	
		if( empty($file) ){	
			$file = $this->request->action;
		}
		// restore_error_handler();
		return $this->view->fetch($file);
	}

	public function fetch($file = '')
	{	
		if( empty($file) ){	
			$file = $this->request->action;
		}
		// restore_error_handler();
		return $this->view->fetch($file);	
	}

}
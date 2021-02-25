<?php
namespace zero;

use zero\facade\Config;
use Nezimi\Template;

class Controller
{
	/**
	 * object 
	 */
	public $app;

	/**
	 * @var string
	 */
	protected $style;

	/**
	 * @var object
	 */ 
	protected $view;

    /**
     * @var array
     */
    protected $replace;

	/**
     * 视图输出字符串内容替换
	 * @var array
	 */ 
	protected $app_config;	
	
	public $model;

	public function __construct(Application $app = null)
	{
		$this->app = $app;
		$this->request = $app->request;
		
		$templateConfig =  $this->app->config->pull('template');

		$this->initialize();
	}

	protected function initialize()
	{}


	public function assign($key, $value)
	{
		$this->view->assign($key, $value);	
	}

	public function fetch(string $template = '')
	{	
		return Response::create($template, 'view');
	}

	public function success()
	{

	}

	public function error()
	{
		
	}
}
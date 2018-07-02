<?php
namespace Zero\library;

use Zero\library\Config;
use Nezumi\MySmarty;

class Controller
{
	/**
	 * @var string
	 */
	protected $style = NULL;

	/**
	 * @var object
	 */ 
	protected $smarty = NULL;

	/**
	 * @var string
	 */ 
	protected $template_dir = NULL;

	/**
	 * @var string
	 */ 
	protected $compie_dir = NULL;


	/**
	 * @var array
	 */ 
	protected $template_config = NULL;	

	/**
	 * @var array
	 */ 
	protected $app_config = NULL;		

	public function __construct($module, $controller, $action)
	{
		// template init
		$this->module = $module;
		$this->controller = $controller;
		$this->action = $action; 

		$this->template_config =  Config::get('template');
		$this->app_config = Config::get('app');

		$this->template_dir = APP_PATH.$this->module.DS.$this->template_config['template_dir'].DS.$this->style.DS;
		$this->compie_dir = RUNTIME_PATH.$this->template_config['compie_dir'].DS.$this->style.DS.$module.DS;
		$this->init_template_engine();
        new URL($this->module);
		new Language($module, strtolower($controller));
		$this->assign('languages', Language::$langs);
		$this->assign('langs', json_encode(Language::$langs));

	}

	protected function init_template_engine()
	{
		$this->smarty = new MySmarty();
		$this->smarty->debug = $this->app_config['app_debug'];  //debug on
		$this->smarty->setTemplateDir($this->template_dir);
		$this->smarty->setCompileDir($this->compie_dir);
		$this->smarty->left_delimiter = $this->template_config['left_delimiter'];
		$this->smarty->right_delimiter = $this->template_config['right_delimiter'];

	}

	protected function init_smarty()
	{
		include_once (ROOT_PATH . 'vendor/smarty/smarty/libs/Smarty.class.php');
		$this->smarty = new \Smarty;
		$this->smarty->debugging    = true;
		$this->smarty->template_dir = $this->template_dir;
		$this->smarty->compile_dir  = $this->compie_dir;
		$this->smarty->caching 	  = false;
	}

	public function assign($key, $value)
	{
		$this->smarty->assign($key, $value);	
	}

	public function display($file = '')
	{	
		if( empty($file) ){	
			$file = $this->action;
		}
		restore_error_handler();
		$this->smarty->display($file);
	}


}
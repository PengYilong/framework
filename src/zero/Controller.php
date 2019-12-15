<?php
namespace zero;

use zero\Config;
use Nezimi\MySmarty;

class Controller
{
	/**
	 * @var string
	 */
	protected $style = NULL;

	/**
	 * @var object
	 */ 
	protected $view = NULL;

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
    protected $replace = NULL;

	/**
     * 视图输出字符串内容替换
	 * @var array
	 */ 
	protected $app_config = NULL;		

	public function __construct(Request $request = null)
	{
		p($request);
		exit();
		$this->template_config =  Config::get('template');
		$this->app_config = Config::get('app');
		$this->style = $this->app_config['admin_style'];
		$this->template_dir = APP_PATH.$this->module.DS.$this->template_config['template_dir'].DS.$this->style.DS;
		$this->compie_dir = RUNTIME_PATH.$this->template_config['compie_dir'].DS.$this->style.DS.$module.DS;
		//视图输出字符串内容替换
        if( !empty($this->app_config['view_replace_str']) ){
            foreach ($this->app_config['view_replace_str'] as $key=>$value){
                $this->replace[$key] = '/static/admin/'.$this->style.'/'.$value;
            }
        }

		$this->initMySmarty();
        new URL($this->module);
		new Language($module, strtolower($controller));
		$this->assign('languages', Language::$langs);
		$this->assign('langs', json_encode(Language::$langs));

	}

    /**
     * init mysmarty
     *
     */
	protected function initMySmarty()
	{
		$this->view = new MySmarty();
		$this->view->debug = $this->app_config['app_debug'];  //debug on
		$this->view->setTemplateDir($this->template_dir);
		$this->view->setCompileDir($this->compie_dir);
		$this->view->left_delimiter = $this->template_config['left_delimiter'];
		$this->view->right_delimiter = $this->template_config['right_delimiter'];
	}

    /**
     * init  template engine of smarty
     */
	protected function init_smarty()
	{
		include_once (ROOT_PATH . 'vendor/smarty/smarty/libs/Smarty.class.php');
		$this->view = new \Smarty;
		$this->view->debugging    = true;
		$this->view->template_dir = $this->template_dir;
		$this->view->compile_dir  = $this->compie_dir;
		$this->view->caching 	  = false;
	}

    /**
     *
     * init template engine of think
     *
     */
	protected  function initThinkTemplateEngine()
    {
        // 设置模板引擎参数
        $config = [
            'view_path'	=>	$this->template_dir,
            'cache_path'	=>	$this->compie_dir,
            'view_suffix'   =>	'html',
        ];

        $this->view = new \think\Template($config);
        $this->view->tpl_replace_string = $this->replace;
    }

	public function assign($key, $value)
	{
		$this->view->assign($key, $value);	
	}

	public function display($file = '')
	{	
		if( empty($file) ){	
			$file = $this->action;
		}
		restore_error_handler();
		return $this->view->fetch($file);
	}

	public function fetch($file = '')
	{	
		if( empty($file) ){	
			$file = $this->action;
		}
		// restore_error_handler();
		return $this->view->fetch($file);	
	}

}
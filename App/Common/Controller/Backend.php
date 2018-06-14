<?php
/**
 * Backend controller init
 * 
 */
namespace App\Common\Controller;

use Zero\library\Controller;
use Zero\library\Factory;

class Backend extends Base
{	
	
	public function __construct($module, $controller,$action)
	{
		$this->style = 'white';
		parent::__construct($module, $controller,$action);
		$static = '/static/'.strtolower($this->module).'/'.$this->style.'/';

		//checks login
		$this->is_login();

	}

	public function is_login()
	{
		$no_check_url = ['login', 'check_login', 'captcha', 'welcome'];
		if( isset($_SESSION['uid']) ){   // for login user

		} else if( !isset($_SESSION['uid']) && !in_array($this->action, $no_check_url) ){
	
		}
	}
}
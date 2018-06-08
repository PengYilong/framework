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
		parent::__construct($module, $controller,$action);
		//checks login
		$this->is_login();
	}

	public function is_login()
	{

	}
}
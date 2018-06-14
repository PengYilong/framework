<?php
/**
 * frontend controller init
 * 
 */
namespace App\Common\Controller;

use Zero\library\Controller;
use Zero\library\Factory;

class Frontend extends Controller
{	
	
	public function __construct($module, $controller,$action)
	{
		$this->style = 'default';
		parent::__construct($module, $controller,$action);
	}
}
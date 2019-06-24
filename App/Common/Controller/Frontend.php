<?php
/**
 * frontend controller init
 * 
 */
namespace app\common\controller;

use zero\Controller;
use zero\Factory;

class Frontend extends Controller
{	
	
	public function __construct($module, $controller,$action)
	{
		$this->style = 'default';
		parent::__construct($module, $controller,$action);
	}
}
<?php
/**
 *	frontend and backend controller init 
 * 
 */
namespace app\common\controller;

use zero\Controller;

class Base extends Controller
{
	protected $is_pjax;	


	public function __construct($module, $controller,$action)
	{
		parent::__construct($module, $controller,$action);
	}

}
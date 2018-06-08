<?php
/**
 * frontend controller init
 * 
 */
namespace App\Common\Controller;

use Zero\library\Controller;
use Zero\library\Factory;

class Frontend extends Base
{	
	
	public function __construct($module, $controller,$action)
	{
		parent::__construct($module, $controller,$action);
		
	}
}
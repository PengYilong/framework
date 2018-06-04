<?php
namespace App\Common\Decorators;
use Zero\library\Controller;

class Template extends Controller
{

	public function before_request()
	{

	}

	public function after_request($result)
	{
		if( isset($_GET['app']) && $_GET['app'] == 'html' ){
			if( !empty($result) ){
				foreach ($result as $key => $value) {
					$this->assign($key, $value);	
				}
			}
			$this->display();
		}	
	}	
}
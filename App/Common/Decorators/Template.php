<?php
namespace App\Common\Decorators;
use zero\Controller;
use app\common\controller\Backend;

class Template
{

	public function before_request()
	{

	}

	public function after_request($result, $object)
	{
		if( isset($_GET['app']) && $_GET['app'] == 'html' ){
			if( !empty($result) ){
				foreach ($result as $key => $value) {
					$object->assign($key, $value);	
				}
				$object->display($result['dtemplate']);
			}
		}	
	}	
}
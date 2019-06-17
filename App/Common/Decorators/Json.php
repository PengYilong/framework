<?php
namespace App\Common\Decorators;
use zero\Controller;

class Json
{


	public function before_request()
	{

	}

	public function after_request($result, $object)
	{
		if( isset($_GET['app']) && $_GET['app'] == 'json' ){
			echo json_encode($result);	
		}	
	}	
}
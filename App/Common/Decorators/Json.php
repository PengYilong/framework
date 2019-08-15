<?php
namespace app\common\decorators;
use zero\Controller;

class Json
{


	public function beforeRequest()
	{

	}

	public function afterRequest($result, $object)
	{
		if( isset($_GET['app']) && $_GET['app'] == 'json' ){
			echo json_encode($result);	
		}	
	}	
}
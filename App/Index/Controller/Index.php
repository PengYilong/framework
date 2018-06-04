<?php
namespace App\Index\Controller;

use Zero\library\Controller;
use Zero\library\Factory;

class Index extends Controller
{
	public function index()
	{
		$model = Factory::getModel('Index');
		$result = $model->getList();
		return [
			'name' => 'Nezumi',
			'title' => 'HelloWorld',
			'code'=> 1,
			'data'=> $result,
		];		
	}

	public function show()
	{
		$model = Factory::getModel('Index');
		$model->getList();
	}

	public function init()
	{
		
	}	
}
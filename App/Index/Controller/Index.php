<?php
namespace App\Index\Controller;

use App\Common\Controller\Frontend;
use Zero\library\Factory;

class Index extends Frontend
{
	public function index()
	{
		$model = Factory::getModel('Account');
		$result = $model->get_name();
		$data = [
			'name' => 'Nezumi',
			'title' => 'HelloWorld',
			'code'=> 1,
			'data'=> $result,
		];
		foreach ($data as $key => $value) {
			$this->assign($key, $value);
		}
		$this->display('public/index.html');
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
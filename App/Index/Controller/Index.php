<?php
namespace app\index\controller;

use App\Common\Controller\Frontend;
use zero\Factory;

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

}
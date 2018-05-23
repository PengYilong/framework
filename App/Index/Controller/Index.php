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
		$this->assign('name', 'Nezumi');
		$this->assign('title', 'HelloWorld');
		$this->assign('code', 1);
		$this->assign('data', $result);
		$this->display();	
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
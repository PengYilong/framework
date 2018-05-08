<?php
namespace App\Index\Controller;

use Zero\library\Controller;

class Index extends Controller
{
	public function index()
	{
		$data = array(
			array('name'=>'Nezumi'),
			array('name'=>'Jimmy'),
			array('name'=>'JameGold'),
		);
		$this->assign('name', 'Nezumi');
		$this->assign('title', 'HelloWorld');
		$this->assign('code', 1);
		$this->assign('data', $data);
		$this->display();	
	}

	public function show()
	{

	}

	public function init()
	{
		
	}	
}
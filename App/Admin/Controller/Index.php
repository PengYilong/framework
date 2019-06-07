<?php
namespace App\Admin\Controller;

use Zero\library\Controller;
use Zero\library\Factory;
use App\Common\Controller\Backend;

class Index 
{	

	public function index()
	{
		exit('run here!');
		$this->assign('pageTitle', '后台管理系统');
		$this->display('public/index.html');
	}	


}
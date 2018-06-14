<?php
namespace App\Admin\Controller;

use Zero\library\Controller;
use Zero\library\Factory;
use App\Common\Controller\Backend;

class Index extends Backend
{	

	public function index()
	{
		$this->assign('pageTitle', '后台管理系统');
		$this->display('public/index.html');
	}	


}
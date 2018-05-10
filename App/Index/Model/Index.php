<?php
namespace App\Index\Model;

use Zero\library\Model;

class Index extends Model
{
	public function getList()
	{
		$result = $this->db->select(array('name','money'), 'cms_account', '', 99, 'id desc','name');
		return $result;
	}		
}
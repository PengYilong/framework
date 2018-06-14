<?php
namespace App\Index\Model;

use Zero\library\Model;

class Account extends Model
{
	public function getList()
	{
		$result = $this->db->select(array('name','money'), $this->table, '', 99, 'id desc','name');
		return $result;
	}		
}
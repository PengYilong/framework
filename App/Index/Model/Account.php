<?php
namespace App\Index\Model;

use zero\Model;
use app\common\model\Base;

class Account extends Base
{
	public function get_name()
	{
		$result = $this->db->select(array('name','money'), $this->table, '', 99, 'id desc','name');
		return $result;
	}		
}
<?php
namespace App\Common\Model;

use Nezumi\Paging;
use Zero\library\Model;
use Zero\library\Factory;
use App\Common\Model\Factory as IFactory;
use Zero\library\Register;

class Base extends Model
{

	/**
	 * @var 
	 */
	protected $db;

	/**
	 * @var 
	 */ 
	protected $cache;

	public function __construct()
	{		
		parent::__construct();
		$this->cache = IFactory::getCache();
	}			

	public function getList($pagesize, $fields='*', $where = '', $limit = '', $order = '', $group = '', $key = '', $having = ''){

		$array = $this->db->get_one('count(*) AS num', $this->table, $where, $limit = '', $order ='', $group, $key, $having);
		$count = $array['num'];

		$paging = Register::get('paging');
		$paging->init($count, $pagesize);
		$limit = $paging->limit;	

		$result = $this->db->select($fields, $this->table, $where, $limit, $order, $group, $key, $having);
		return [
			'data' => $result,
			'page' => '<div class="pagelist">'.$paging->html().$paging->go_page().'</div>',
		];	
	}

	/**
	 * 
	 * 
	 */
	public function init_paging($count, $pagesize)
	{	
		$paging->init($count, $pagesize);
		$file = './paging.html';
		$go_page_file = './go_page.html';	
		$limit = $paging->limit;	
	}
}
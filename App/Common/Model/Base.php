<?php
namespace App\Common\Model;

use Nezumi\Paging;
use Zero\library\Model;
use Zero\library\Factory;
use App\Common\Model\Factory as IFactory;
use Zero\library\Register;
use Zero\library\Config;

class Base extends Model
{

	/**
	 * @var string prefix 
	 */
	protected $prefix;

	/**
	 * @var string name of table
	 */
	protected $table = NULL;


	/**
	 * @var 
	 */ 
	protected $cache;

	public function __construct()
	{	
		parent::__construct();
		$db_config = Config::get('database');
		if( $db_config ){
			$db_config = $db_config['master'];
			$this->prefix = $db_config['tablepre']; 
			$this->table = $this->getModelName();
			$this->options['table'] = $this->table;
			$this->cache = IFactory::getCache();
		}
	}			

    public function getModelName()
    {
        $sub_arr = explode('\\', get_class($this));
        $sub_class = end($sub_arr);
        return  $this->prefix.toUnderscore($sub_class);
    }

	public function getList($pagesize, $fields='*', $where = '', $table='', $join= '', $order = '', $group = '', $having = ''){
		$cond = 'mark=1';
		$cond .= $where; 
		$array = $this->fields('count(*) AS num')->table($this->table)->where($cond)->get_one(); 
		$count = $array['num'];

		$paging = Register::get('paging');
		$paging->init($count, $pagesize);
		$limit = $paging->limit;	

		$result = $this->fields($fields)->where($cond)->join($join)->limit($limit)->order($order)->group($group)->having($having)->select();
		$pageHtml = '';
		if( $pagesize<$count ){
			$pageHtml = '<div class="pagelist">'.$paging->html().$paging->go_page().'</div>';			
		}

		return [
			'data' => $result,
			'page' => $pageHtml,
		];	
	}

	public function getData($fields='*', $where = '', $table='', $join= '', $order = '', $group = '', $having = ''){
		$cond = 'mark=1';
		$cond .= $where; 
		$result = $this->fields($fields)->where($cond)->join($join)->order($order)->group($group)->having($having)->select();
		return $this->resetData($result);
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
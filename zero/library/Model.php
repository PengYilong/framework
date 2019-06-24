<?php
namespace zero;
use Nezumi\Paging;
use Nezumi\MySQLi;
use zero\Config;

class Model extends MySQLi 
{

	/**
	 * @var
	 */
	public $db;

	public function __construct()
	{
        $this->getDatabase();
        $this->db = $this;
	}

    public function getDatabase( $id = 'master' )
    {
        $key = 'database_'.$id;
        $database_config = Config::get('database');
        if( empty($database_config) ){
            return false;
        }
        if( $id == 'master' ){
            $db_config = $database_config['master'];
        } else {
            $db_config = $database_config[array_rand($database_config['slave'])];
        }
        $db = Register::get($key);
        if( !$db ){
            $db = $this->open($db_config);
            Register::set($key, $db);
        }
    }

}
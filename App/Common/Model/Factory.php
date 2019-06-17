<?php
namespace App\Common\Model;

use zero\Register;
use zero\Config;

class Factory
{

    public static function getCache()
    {
        $key = 'cache_memcahed';
        $cache = Register::get($key);
        if( !$cache ){
            $cache = new \Nezumi\Memcached();
            $cache_conf = Config::get('cache');
            $cache->open($cache_conf);
            Register::set($key, $cache);
        }
        return $cache;
    } 

    public static function get_paging($page, $file, $go_page_file)
    {
        $key = 'paging';
        $paging = Register::get($key);
        if(!$paging){
            $paging = new \Nezumi\Paging($file, $go_page_file);
            $paging->page_name = $page;
            Register::set($key, $paging);
        }
        return $paging;
    }
}

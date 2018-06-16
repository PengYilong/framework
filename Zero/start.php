<?php

//php version must greater than 5.4
if( phpversion()<'5.4' ){
	exit('php version must greater that 5.4');
}
date_default_timezone_set('PRC');

//define ENV constant
define('DS', DIRECTORY_SEPARATOR);
define('SITE_PATH', dirname($_SERVER['SCRIPT_FILENAME']).DS);
defined('APP_PATH') or define('APP_PATH', SITE_PATH.'App');
define('ROOT_PATH', dirname(realpath(APP_PATH)).DS);
define("ZERO_PATH", __DIR__.DS);
define('EXT', '.php');
define('CONF_PATH', ROOT_PATH.'configs'.DS);
define('CORE_CONF_PATH', ZERO_PATH.'configs'.DS);
define('CONF_EXT', '.php');
define('RUNTIME_PATH', ROOT_PATH.'runtime'.DS);

//common constant
define('IS_POST', $_SERVER['REQUEST_METHOD']=='POST' ? true : false);
define('IS_GET',  $_SERVER['REQUEST_METHOD']=='GET' ? true : false);

//autoloading classes
include ZERO_PATH.'library/Loader.php';
spl_autoload_register('Zero\library\Loader::_autoload');

//load functions
include ZERO_PATH.'common'.DS.'function'.EXT;
include APP_PATH.'Common'.DS.'Function'.DS.'function'.EXT;

//load composer
if( file_exists(ROOT_PATH.'vendor/autoload.php') ){
	require ROOT_PATH.'vendor/autoload.php';
}

$configs = array(
	CORE_CONF_PATH,
	CONF_PATH,	
);
//load configs and
use Zero\library\Config;
new Config($configs, CONF_EXT);

//to init handling error and exception class
$config = Config::get('log');
$path = RUNTIME_PATH.'log'.DS;
$rule = $config['rule'];
$app = Config::get('app');
new \Nezumi\MyError($path, $rule, ZERO_PATH.'/template/error.php', $app['app_debug']);

session_start();

//route init
new Zero\library\Route();





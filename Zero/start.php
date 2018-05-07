<?php
//define constant
define('DS', DIRECTORY_SEPARATOR);
define("ZERO_PATH", __DIR__.DS);
define('EXT', '.php');
define('CONF_EXT', '.php');
define('SITE_PATH', ZERO_PATH.'..'.DS);
defined('APP_PATH') or define('APP_PATH', SITE_PATH.'App/');  //define App constant

//autoloading classes
include ZERO_PATH.'library/Loader.php';
spl_autoload_register('Zero\library\Loader::_autoload');

//load functions
include  ZERO_PATH.'common'.DS.'function'.EXT;

//load configs and 
new Zero\library\Config(ZERO_PATH.'configs'.DS, CONF_EXT);

//load log
new Zero\library\Log();


//load route
new Zero\library\Route();

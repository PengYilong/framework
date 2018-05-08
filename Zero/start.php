<?php
//define constant
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(realpath(APP_PATH)).DS);
define("ZERO_PATH", __DIR__.DS);
define('SITE_PATH', dirname($_SERVER['SCRIPT_FILENAME']).DS);
define('EXT', '.php');
define('CORE_CONF_PATH', ZERO_PATH.'configs'.DS);
define('CONF_EXT', '.php');
define('RUNTIME_PATH', ROOT_PATH.'runtime'.DS);

//autoloading classes
include ZERO_PATH.'library/Loader.php';
spl_autoload_register('Zero\library\Loader::_autoload');

//load functions
include  ZERO_PATH.'common'.DS.'function'.EXT;

//load configs and
new Zero\library\Config(CORE_CONF_PATH, CONF_EXT);

//log init
new Zero\library\Log();

//route init
new Zero\library\Route();





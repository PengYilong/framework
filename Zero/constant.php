<?php
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
define('LANGUAGE_PATH', ZERO_PATH.'language'.DS);

//common constant
define('IS_POST', $_SERVER['REQUEST_METHOD']=='POST' ? true : false);
define('IS_GET',  $_SERVER['REQUEST_METHOD']=='GET' ? true : false);

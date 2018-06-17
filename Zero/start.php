<?php
//php version must greater than 5.4
if( phpversion()<'5.4' ){
	exit('php version must greater that 5.4');
}

//load constant
require __DIR__.'/constant.php';

//load functions
include ZERO_PATH.'common'.DS.'function'.EXT;
include APP_PATH.'Common'.DS.'Function'.DS.'function'.EXT;

//autoloading classes
include ZERO_PATH.'library/Loader.php';
spl_autoload_register('Zero\library\Loader::_autoload');

//load composer
if( file_exists(ROOT_PATH.'vendor/autoload.php') ){
	require ROOT_PATH.'vendor/autoload.php';
}


$config = require CONF_PATH.'app.php';
(new Zero\library\Application($config))->run(); 







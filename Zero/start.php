<?php
define("ZERO_PATH", __DIR__.'/');
define("SITE_PATH", ZERO_PATH.'../');
include ZERO_PATH.'library/Loader.php';
spl_autoload_register('Zero\library\Loader::_autoload');
new Zero\library\Application;
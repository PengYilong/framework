<?php
namespace zero;

//load constant
require __DIR__ . '/constant.php';

//autoloading classes
require __DIR__ . '/library/ClassLoader.php';
ClassLoader::register();
// $class = new \ReflectionClass('zero\ClassLoader');
// $arr = $class->getStaticProperties();
// p($arr);
$index = new \app\api\controller\index();
$index->index();
exit();
$config = require CONF_PATH.'app.php';
(new Zero\library\Application($config))->run();

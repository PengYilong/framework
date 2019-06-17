<?php
namespace zero;

//load constant
require __DIR__ . '/constant.php';

//autoloading classes
require __DIR__ . '/library/ClassLoader.php';
ClassLoader::register();

(new Application())->run();

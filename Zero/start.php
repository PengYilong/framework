<?php
namespace zero;

//autoloading classes
require __DIR__ . '/library/ClassLoader.php';

ClassLoader::register();

Container::get('app', [22])->run();

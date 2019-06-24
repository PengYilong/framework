<?php
namespace zero;

//autoloading classes
require __DIR__ . '/library/ClassLoader.php';
require __DIR__ . '/constant.php';

ClassLoader::register();

classLoader::addClassAlias([
    'Application' => facade\Application::class,
]);
Container::get('Application')->run();



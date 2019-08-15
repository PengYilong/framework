<?php
namespace zero;

//autoloading classes
require __DIR__ . '/library/ClassLoader.php';
require __DIR__ . '/constant.php';

ClassLoader::register();

classLoader::addClassAlias([
    'Application' => facade\Application::class,
    'Route' => facade\Route::class,
]);
Container::get('application')->run()->send();



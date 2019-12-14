<?php
namespace zero;

//autoloading classes
require __DIR__ . '/library/Loader.php';
require __DIR__ . '/constant.php';

Loader::register();

Loader::addClassAlias([
    'Application' => facade\Application::class,
    'Route' => facade\Route::class,
]);
Container::get('application')->run()->send();



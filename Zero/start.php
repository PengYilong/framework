<?php
namespace zero;

//autoloading classes
require __DIR__ . '/library/ClassLoader.php';

ClassLoader::register();

classLoader::addClassAlias([
    'Application' => facade\Application::class,
]);

(Container::get('\Application'))::init('22');

// $ref = new \ReflectionClass('zero\classLoader');
// $arr = $ref->getStaticProperties();
// p($arr);
// Container::get('Application', [22])->run();



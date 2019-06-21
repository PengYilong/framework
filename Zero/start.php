<?php
namespace zero;

//autoloading classes
require __DIR__ . '/library/ClassLoader.php';

ClassLoader::register();

classLoader::addClassAlias([
    'Application' => facade\Application::class,
]);

// $class = new \Application();
// echo $class->test();

echo Container::get('\Application')->test();
// $ref = new \ReflectionClass('zero\classLoader');
// $arr = $ref->getStaticProperties();
// p($arr);
Container::get('Application', [22])->run();

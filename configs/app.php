<?php
$array = [
	'app_debug' => true,
	'url_model' => 1,
	'language' => 'zh-cn',
	'conponents' => [
		'database' => require __DIR__.'/database.php',
		'decorators' => require __DIR__.'/decorators.php',
		'log' => require __DIR__.'/log.php',
		'route'=> require __DIR__.'/route.php',	
		'template'=> require __DIR__.'/template.php',	
	]
];
return $array;
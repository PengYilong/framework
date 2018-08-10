<?php
$array = [
	'app_debug' => true,
	'url_model' => 2,
	'language' => 'zh-cn',
	'conponents' => [
		'decorators' => require __DIR__.'/decorators.php',
		'log' => require __DIR__.'/log.php',
		'route'=> require __DIR__.'/route.php',	
		'template'=> require __DIR__.'/template.php',	
	],
    'admin_style' => 'layui',
    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__CSS__'      => '/static/css',
        '__IMG__'      => '/static/images',
        '__JS__'       => '/static/js',
    ],
];
return $array;
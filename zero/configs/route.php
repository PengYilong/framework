<?php
/**
 * 
 * 默认路由配置,如果不输入地址，走的是,/home/test
 * 
 * 
 */
$array = array(
	'default' => array(
		'module' => 'Index',
		'controller' =>'Index',
		'action' => 'index',
	),
);

return $array;
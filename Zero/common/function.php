<?php
//公用函数库

function p($var)
{
	if( is_bool($var) ){
		var_dump($var);
	} elseif( is_null($var) ){
		var_dump(NULL);
	} else {
		echo '<pre style="position:relative;z-index:1000;padding:10px;border-radius:5px;background:#f5f5f5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;">'.print_r($var, true).'</pre>';
	}
}


function new_addslashes($params)
{
	if(!is_array($params)){
		return addslashes($string);
	}
	if( empty($params) ){
		return false;
	}
	foreach ($params as $key => $value) {
		$params[$key] = addslashes($value);			
	}
	return $params;
}
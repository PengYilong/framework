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

/**
 * APP返回操作信息.
 *
 * @author  Nezumi
 *
 * @param string $msg
 * @param bool   $success
 * @param mixed  $data
 *
 * @return array
 */
function msg($msg = 'success', $status = 1, $data = false)
{
    return array(
        'result' => $msg,
        'status' => $status,
        'data' => $data,
    );
}

/**
 * 搜索条件.
 *
 * @author  Nezumi
 *
 * @param   $data = array(
 *                'likearrayName' => '模糊条件',
 *                'equalarrayName' => '等于条件'
 *                );
 *
 * @return array
 */
function search_condition($data = array())
{
    //声明
    $likearrayName = !empty($data['likearrayName']) ? $data['likearrayName'] : array();     //模糊搜索
    $equalarrayName = !empty($data['equalarrayName']) ? $data['equalarrayName'] : array();   //确切搜索
    $cond = '1=1';
    $searcharray = array();

    foreach ($data as $key => $value) {
        if ($value || 0 === $value || '0' === $value) {
            if (array_key_exists($key, $equalarrayName)) {
                $cond .= " AND $equalarrayName[$key] = '$value'";
                $searcharray[$key] = $value;
            }
            if (array_key_exists($key, $likearrayName)) {
                $cond .= " AND $likearrayName[$key] like '%$value%' ";
                $searcharray[$key] = $value;
            }
        }
    }

    //返回
    $result['cond'] = $cond;
    $result['searcharray'] = $searcharray;

    return $result;
}

/**
 * 返回经过addslashes处理过得函数
 * @param sring or array $params  
 * @return  sring or array
 */
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
<?php
/**
 * common functions
 * 
 */

/**
 * print_r
 * 
 */
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
 * APP return 
 *
 *
 * @param string $msg
 * @param bool   $success
 * @param mixed  $data
 *
 * @return array
 */
function msg($msg = 'success', $status = 0, $data = FALSE)
{
    return [
        'message' => $msg,
        'status' => $status,
        'data' => $data,
    ];
}

/**
 * search condition
 *
 *
 * @param   $data = array(
 *                    'likearrayName' => 'like contition',
 *                    'equalarrayName' => 'equal condition'
 *                );
 *
 * @return array
 */
function search_condition($data = [])
{
    if( empty($data) ){
        return FALSE;
    }
    //statement
    $likearrayName = !empty($data['likearrayName']) ? $data['likearrayName'] : [];     
    $equalarrayName = !empty($data['equalarrayName']) ? $data['equalarrayName'] : []; 
    $cond = '1=1';
    $searcharray = array();

    foreach ($data as $key => $value) {
        if ($value) {
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

    //return
    $result['cond'] = $cond;
    $result['searchArray'] = $searcharray;

    return $result;
}

/**
 * return to be handle  each elements of array  via  addslashes function
 * @param sring or array $params  
 * @return  sring or array
 */
function new_addslashes($params)
{
    if(!is_array($params)){
        return addslashes($string);
    }
    if( empty($params) ){
        return FALSE;
    }
    foreach ($params as $key => $value) {
        $params[$key] = addslashes($value);         
    }
    return $params;
}

function go_url($url)
{
    return '<script type="text/javascript">location.href="'.$url.'"</script>';
}

/**
 *  AdminMember to admin_member  
 * 
 */
function to_underscore($str)
{
    $dstr = preg_replace_callback('/([A-Z]{1})/',function($matchs)
    {
        return '_'.strtolower($matchs[0]);
    },$str);
    return ltrim($dstr, '_');
}

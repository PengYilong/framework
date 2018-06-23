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
function msg($msg = 'failed', $status = 0, $data = FALSE)
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
 * @return array
 */
function searchCondition($data = [])
{
    $result = [];
    if( empty($data) ){
        return $result;
    }
    //statement
    $conds = [
        'equalCond',
        'likeCond',
        'sTimeCond',
        'eTimeCond',
    ];

    $cond = '1=1';
    $searcharray = [];

    foreach ($conds as $key => $value) {
        if (array_key_exists($value, $data)) {
           if ( $value=='equalCond'){
                foreach ($data[$value] as $k => $v) {
                    if($v){
                        $cond .= ' AND '. $k .'='.$v;
                        $searcharray[$k] = $v;
                    }
                }
            }
           if ( $value=='likeCond'){
                foreach ($data[$value] as $k => $v) {
                    if($v){
                        $cond .= ' AND '. $k.' like \'%'.$v.'%\'';
                        $searcharray[$k] = $v;
                    }
                }
            }
           if ( $value=='sTimeCond'){
                foreach ($data[$value] as $k => $v) {
                    if($v){
                        $cond .= ' AND'. $k.$v['symbol'].strtotime($v['value']);
                        $searcharray['sTimeCond'][$k] = $v['value'];
                    }
                }
            }
           if ( $value=='eTimeCond'){
                foreach ($data[$value] as $k => $v) {
                    if($v){
                        $cond .= ' AND $ke'.$v['symbol'].strtotime($v['value']);
                        $searcharray['eTimeCond'][$k] = $v['value'];
                    }
                }
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
        return addslashes($params);
    }
    if( empty($params) ){
        return FALSE;
    }
    foreach ($params as $key => $value) {
        $params[$key] = new_addslashes($value);         
    }
    return $params;
}

function goUrl($url)
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


/**
 * @param array $array  
 * @param int $position position of to insert array
 * @param to insert array 
 */
function array_insert ($array, $position, $insert_array) {
    $first_array = array_splice ($array, 0, $position);
    return array_merge ($first_array, $insert_array, $array);
}
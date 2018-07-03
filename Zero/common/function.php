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
 * @param array $data
 * @param array $subject $_GET $_POST
 * @return array
 */
function searchCondition($data = [], $subject = [],  $first = true)
{
    $result = [];
    if( empty($data) ){
        return $result;
    }
    //statement
    $conds = [
        'equal',
        'like',
        'startDate',
        'date',
    ];

    $cond = '';
    $searchArray = [];

    foreach ($conds as $key => $value) {
        if (array_key_exists($value, $data)) {
            foreach ($data[$value] as $k => $v) {
                if( !empty($subject[$k]) ){
                    $link = !$first ? ' AND ' : '';
                    switch ($value) {
                        case 'equal':
                            $cond .= $link. $v .'='.$subject[$k];
                            $searchArray[$k] = $subject[$k];                   
                            break;
                        case 'like':
                            $cond .= $link. $k.' like \'%'.$subject[$k].'%\'';
                            $searchArray[$k] = $subject[$k]; 
                            break;                         
                        case 'date':
                            $cond .= $link. $v['value'].$v['symbol'].strtotime( $subject[$k] );
                            $searchArray[$k] = $subject[$k];
                            break;                                              
                    }
                    $first = false;
                }
            }
        }
    }

    //return
    $result['cond'] = $cond;
    $result['searchArray'] = $searchArray;

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
function toUnderscore($str)
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
function arrayInsert ($array, $position, $insert_array) {
    $first_array = array_splice ($array, 0, $position);
    return array_merge ($first_array, $insert_array, $array);
}

/**
 * array to select
 * @param 
 * @param  
 */
function arrayToSelect($arr = [], $default = '', $field = '')
{
    if( empty($arr) ){
        return false;
    }
    $options = '';
    foreach ($arr as $key => $value) {
        $selected = $default == $key ? 'selected' : '';
        if( $field ){
            $options .= '<option value="'.$key.'"  '.$selected.'>'.$value[$field].'</option>';
        } else {
            $options .= '<option value="'.$key.'"  '.$selected.'>'.$value.'</option>';
        }
    }
    return $options;
}
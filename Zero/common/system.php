<?php
/**
 * language conversion
 */
function language($language = 'no_language')
{
    static $LANG = array();
    $lang = 'en';
    if (defined('SYS_STYLE')){
        $lang = SYS_STYLE;
    }
   
    $system = LANGUAGE_PATH.$lang.'/system.lang.php'; 
    $system_menu = LANGUAGE_PATH.$lang.'/system_menu.lang.php';
    $module = LANGUAGE_PATH.$lang.'/'.ROUTE_M.'.lang.php';
    
    $system =  str_replace('/', DC, $system); 
    $system_menu =  str_replace('/', DC, $system_menu);
    $module = str_replace('/', DC, $module);

    if(!$LANG){
        include_once $system;
        include_once $system_menu;
        if(file_exists($module)){
            include_once $module;
        }
    }
    if(array_key_exists($language, $LANG)){
        $language = $LANG[$language];
    }
    // print_r($LANG);
    // echo $LANG['sys_setting'];
    return $language;
}
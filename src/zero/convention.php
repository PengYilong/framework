<?php
return [
    'app' => [
        'app_namespace' => 'app',
        'app_debug' => true,
        'language' => 'zh-cn',
        'enable_myerror' => true,
        // 后台默认风格
        'admin_style' => 'layui',
        // 视图输出字符串内容替换
        'view_replace_str' => [
            '__css__'      => 'css',
            '__img__'      => 'images',
            '__js__'       => 'js',
            '__boot__'     => 'bootstrap',
            '__font__'     => 'font',
            '__lay__'      => 'layui',
            '__lib__'      => 'js/lib',
            '__plug__'     => 'js/plugins',
        ],
        // 默认的访问控制器层文件夹
        'url_controller_layer'=> 'controller',
        'url_controller_bussiness'=> 'bussiness',
        'url_controller_model'=> 'model',
        'pathinfo_depr' => '/',
        'app_multi_module' => true,
        //0 key/value  1 order
        'url_param_type' => 0,
        'app_multi_module' => true,
        'default_return_type' => 'html',
        'default_timezone' => 'Asia/Shanghai',
        'pathinfo_depr' => '/',
        'default_return_type' => 'html',
        'default_ajax_return_type' => 'json',
        // +-----------------------------
        // | module settings
        // +-----------------------------
        'default_module' => 'index',
        'default_controller' => 'Index',
        'default_action' => 'index',
        'controller_auto_search' => true,
        'deny_module_list' => ['common'],
        //自动搜索控制器
        'controller_auto_search' => true,
        // +----------------------------------------------------------------------
        // | URL settings
        // +----------------------------------------------------------------------
        'url_lazy_route' => false,
    ],
    'database' => [
        'master' => array (
            'hostname' => 'localhost',
            'database' => 'myadmin',
            'username' => 'root',
            'password' => 'pyl',
            'tablepre' => 'cms_',
            'charset' => 'utf8',
            'type' => 'mysqli',
            'debug' => true,
            'pconnect' => 0,
            'autoconnect' => 0,
        ),
    ],
    'decorators' => [
        'output_decorators' => [
            'app\common\decorators\Json',	
            'app\common\decorators\Template',		
        ]
    ],
    'log' => [
        'rule' => [
            'Y',
            'm',
            'd',
        ],
    ],
    'route' => [
        'default' => [
            'module' => 'Index',
            'controller' =>'Index',
            'action' => 'index',
        ],
    ],
    'template' => [
        'left_delimiter' => '{',
        'right_delimiter' => '}',
        'template_dir' => 'View',
        'compie_dir'=> 'templates_c',  
    ],
];
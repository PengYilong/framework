<?php
return [
    'app' => [
        'app_debug' => true,
        'url_model' => 2,
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
        'default_timezone' => 'PRC',
        'bind_modules' => [
            'admin'=>'admin',
            'api'=>'api',
        ],
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
            'App\Common\Decorators\Json',	
            'App\Common\Decorators\Template',		
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
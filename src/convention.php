<?php
use zero\facade\Env;

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
        
        'default_timezone' => 'Asia/Shanghai',
        'pathinfo_depr' => '/',
        'default_return_type' => 'html',
        'default_ajax_return_type' => 'json',
        // +-----------------------------
        // | module settings
        // +-----------------------------
        //是否支持多模块
        'app_multi_module' => true,
        'default_module' => 'api',
        'default_controller' => 'Index',
        'default_action' => 'index',
        'controller_auto_search' => true,
        'deny_module_list' => ['common'],
        //自动搜索控制器
        'controller_auto_search' => true,

        // +----------------------------------------------------------------------
        // | URL Settings
        // +----------------------------------------------------------------------
        'var_method' => '_method',
        'dispatch_success_tmpl' => __DIR__ . 'tpl/dispatch_jump.tpl',
        'dispatch_fail_tmpl' => __DIR__ . 'tpl/dispatch_jump.tpl',
        // +----------------------------------------------------------------------
        // | Route Settings
        // +----------------------------------------------------------------------
        'pathinfo_depr' => '/',
        'url_lazy_route' => false,
        //强制路由(路由是否完全匹配)
        'route_complete_match'   => false,
        'default_route_pattern' => '\w+',
    ],
    'database' => [
        // 数据库库类型
        'type' => 'mysql',
        // 服务器地址
        'hostname' => 'localhost',
        // 数据库名
        'database' => 'english',
        // 用户名
        'username' => 'root',
        // 密码
        'password' => 'pyl',
        // 端口
        'hostport' => '',
        // 连接DSN
        'dsn' => '',
        // 数据库连接参数
        'params' => '',
        // 数据库编码默认采用utf8
        'charset' => 'utf8',
        // 数据库表前缀
        'prefix' => '',
        // 数据库调试模式
        'debug' => false,
        // 是否长链接
        'pconnect' => 0,
        'autoconnect' => 0,
        // 是否严格检查字段是否存在
        'fields_strict' => true,
        // 数据集返回类型
        'resultset_type' => 'array',
        // 开启自动写入时间戳字段
        'auto_timestamp' => false,
        // 0-单一服务器 1-分布式服务器
        'deploy' => 0,
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
<?php
use zero\facade\Env;

return [
    'app' => [
        'app_namespace' => 'app',
        'app_debug' => true,
        'language' => 'zh-cn',
        'enable_myerror' => true,
        
        // 默认的访问控制器层文件夹
        'url_controller_layer'=> 'controller',
        'url_controller_model'=> 'model',
        'pathinfo_depr' => '/',
        
        'default_timezone' => 'Asia/Shanghai',
        'pathinfo_depr' => '/',
        'default_return_type' => 'html',
        'default_ajax_return_type' => 'json',
        // +-----------------------------
        // | module settings
        // +-----------------------------
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
    'log' => [
        // 日志记录方式
        'type' => 'File',
        // 允许记录的日志级别   
        'level' => [],
        // 日志保存目录
        'path' => '../runtime/',
        // 日志输出格式
        'time_format' => 'c',
        // 单文件日志写入
        'single' => false,
        // 日志文件大小限制
        'file_size' => 2097152,
        // 独立日志级别
        'apart_level' => [],
        // 最大日志文件数量（超过自动清理）
        'max_files' => 0,
        'json' => false,
        'json_options' => JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        // 允许记录的日志级别   
        'format' => '[%s][%s] %s',
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
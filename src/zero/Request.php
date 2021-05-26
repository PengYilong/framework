<?php
namespace zero;

class Request
{

    protected $config = [];

    /**
     * 请求类型
     * @var string
     */
    protected $method;

    /**
     * current URL
     *
     * @var string
     */
    protected $url;

    /**
     * @var array $_SERVER object 
     */
    public $server = [];

    /**
     * 
     * @var string current module
     */
    public $module;

    /**
     * 
     * @var string current controller
     */
    public $controller;

    /**
     * 
     * @var string current action
     */
    public $action;

    /**
     * @var array route variables
     */
    public $route = [];

    protected $pathinfo;

    /**
     * current get params
     * @var array 
     */
    protected $get = [];

    /**
     * current post params
     * @var array 
     */
    protected $post = [];

    /**
     * current put params
     * @var array 
     */
    protected $put = [];

    /**
     * current request params
     * @var array 
     */
    protected $request = [];

    /**
     * 是否合并过Param
     *
     * @var boolean
     */
    protected $mergeParam = false;

    /**
     * 合并的参数
     *
     * @var array
     */
    protected $param = [];

    /**
     * header params
     *
     * @var array
     */
    protected $header = [];

    public function __construct(Application $app, Config $config)
    {
        $this->server = $_SERVER;
        $this->config = $config->pull('app');
        
        $this->input = file_get_contents('php://input');
        
        if( function_exists('apache_request_header') && $result = apache_request_header() ) {
            $header = $result;
        } else {
            $server = $this->server;
            $header = [];

            foreach($server as $key => $val) {
                if(0 === strpos($key, 'HTTP_')) {
                    $key = str_replace('_', '-', strtolower(substr($key, 5)));
                    $header[$key] = $val;
                }
            }

            if( isset($server['CONTENT_TYPE']) ) {
                $header['content-type'] = $server['CONTENT_TYPE']; 
            }

            if( isset($server['CONTENT_LENGTH']) ) {
                $header['content-length'] = $server['CONTENT_LENGTH']; 
            }

            $this->header = array_change_key_case($header);
        }

        $inputData = $this->getInputData($this->input);

        $this->get = $_GET;
        $this->post = $_POST ?: $inputData;
        $this->request = $_REQUEST;
        $this->put = $inputData;
    }

    public function getInputData($content): array
    {
        $contentType = $this->contentType();
        
        if('application/x-www-form-urlencoded' == $contentType) {
            parse_str($content, $data);
            return $data;
        } elseif( false !== strpos($contentType, 'json')) {
            $data = json_decode($content, true);
            return $data;
        }
        return [];
    }

    public function contentType(): string
    {
        $contentType = $this->header('Content-Type');

        if($contentType) {
            return trim($contentType);
        }

        return '';
    }

    /**
     * setting or getting current header
     *
     * @param string $name
     * @param string $default
     * @return void
     */
    public function header(string $name = '', string $default = null)
    {
        if('' === $name) {
            return $this->header;
        }

        $name = str_replace('_', '-', strtolower($name));

        return $this->header[$name] ?? $default;
    }

    public function pathinfo()
    {
        $pathinfo = $this->server['PATH_INFO']; 
        $this->pathinfo = empty($pathinfo) || '/' == $pathinfo ? '' : ltrim($pathinfo, '/');
        return $this->pathinfo; 
    }

    /**
     * @return bool
     */
    public function isMethod(string $method) : bool
    {
        return $this->method() == $method;
    }

    /**
     * gets request method 
     *
     * @param boolean $origin
     * @return void
     */
    public function method($origin = false)
    {
        if($origin){
            return $this->server['REQUEST_METHOD'] ?: 'GET'; 
        } elseif(!$this->method) {
            if( isset($_POST[$this->config['var_method']]) ) {
                $method = strtolower($_POST[$this->config['var_method']]);
                if( in_array($method, ['get', 'post', 'put', 'patch', 'delete']) ) {
                    $this->method = strtoupper($method);
                } else {
                    $this->method = 'POST';
                }
                unset($_POST[$this->config['var_method']]);
            } else {
                $this->method = $this->server['REQUEST_METHOD'] ?: 'GET'; 
            }
        }
        return $this->method;
    }

    /**
     * e.g.  getRootDomain(getapi.zero.own) = zero.own
     */
    public function getRootDomain() :string
    {
        $root = $this->server['HTTP_HOST'];
        $array = explode('.', $root);
        $num = count($array);
        return $num>1 ? $array[$num-2] . '.' . $array[$num-1] : $array[0];
    }
    
    public function isAjax()
    {
        $result = false;
        if( !empty($this->server['HTTP_X_REQUESTED_WITH']) && $this->server['HTTP_X_REQUESTED_WITH'] == 'xmlhttprequest' ){
            $result = true;
        }
        return $result;
    }

    public function setRouteVars(array $route)
    {
        $this->route = array_merge($this->route, $route);
        return $this;
    }

    /**
     * 强制类型转换
     *
     * @param [type] $data
     * @param string $type
     * @return void
     */
    private function typeCast(&$data, string $type)
    {
        switch( strtolower($type) ) {
            //数组
            case 'a':
                $data = (array) $data;
                break;
            //数字
            case 'd':
                $data = (int) $data;
                break;
            // 浮点
            case 'f':
                $data = (float) $data;
            // 布尔
            case 'b':
                $data = (boolean) $data;
            // 字符串
            case 's':
                if ( is_scalar($data) ) {
                    $data = (string) $data;
                } else {
                    throw new \InvalidArgumentException('The variable type is error: ' . gettype($data));
                }
                break;
        }
    }

    /**
     * Get the value of the get params 
     *
     * @param string|array $name 变量名
     * @param mixed $default 默认值
     * @param string|array $filter 过滤方法
     * @return void
     */
    public function get($name = '', $default = null)
    {
        return $this->input($this->get, $name, $default);
    }

    /**
     * Get the value of the post params 
     *
     * @param string|array $name 变量名
     * @param mixed $default 默认值
     * @param string|array $filter 过滤方法
     * @return void
     */
    public function post($name = '', $default = null)
    {
        return $this->input($this->post, $name, $default);
    }

    /**
     * Get the value of the put params 
     *
     * @param string|array $name 变量名
     * @param mixed $default 默认值
     * @param string|array $filter 过滤方法
     * @return void
     */
    public function put($name = '', $default = null)
    {
        return $this->input($this->put, $name, $default);
    }

    /**
     * Get the value of the put params 
     *
     * @param string|array $name 变量名
     * @param mixed $default 默认值
     * @param string|array $filter 过滤方法
     * @return void
     */
    public function route($name = '', $default = null)
    {
        return $this->input($this->route, $name, $default);
    }

    /**
     * Get the value
     *
     * @param string|array $name 变量名
     * @param mixed $default 默认值
     * @param string|array $filter 过滤方法
     * @return void
     */
    public function param($name = '', $default = null)
    {
        $method = $this->method(true);

        // 第一次获取全部函数, 第二次不合并
        if( empty($this->mergeParam) ) {
            // 自动获取请求变量
            switch($method) {
                case 'POST':
                    $vars = $this->post(false);
                    break;
                case 'PUT':
                case 'DELETE':
                case 'PATCH':
                    $vars = $this->put(false);
                    break;
                default:
                    $vars = [];
            }
            
            // 当前请求参数和URL地址中的参数合并
            $this->param = array_merge($this->param, $this->get(false), $vars, $this->route);

            $this->mergeParam = true;
        }

        return $this->input($this->param, $name, $default);
    }

    /**
     * get the param value of methods(get,post,put)
     *
     * @param array $data
     * @param string $name
     * @param [type] $default
     * @param string $filter
     * @return void
     */
    public function input(array $data = [], $name = '', $default = null)
    {
        if( false === $name ) {
            return $data;
        }

        $name = (string) $name;

        if('' != $name) {

            if( strpos($name, '/') ) {
                list($name, $type) = explode('/', $name);
            }
        
            $data = $this->getData($data, $name);

            if( is_null($data) ) {
                return $default;
            }
        }

        if( isset($type) && $data !== $default ) {
            $this->typeCast($data, $type);
        }

        return $data;
    }
    
    /**
     * get the name value of methods(get,post,put);
     *
     * @param array $data
     * @param [type] $name
     * @return void
     */
    protected function getData(array $data, string $name, $default = null)
    {
        return $data[$name] ?? $default;
    }
}
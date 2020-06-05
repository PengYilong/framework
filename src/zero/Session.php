<?php
namespace zero;

class Session
{
    /**
     * 配置参数
     * @var array
     */
    protected $config = [];

    /**
     * 作用域
     *
     * @var string
     */
    protected $prefix = '';

    /**
     * 是否初始化
     */
    protected $init;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }   

    /**
     * session 初始化
     *
     * @param array $config
     * @return void
     */
    public function init(array $config = [])
    {
        $config = $config ?: $this->config;

        $isDoStart = false;

        // session start
        if( !empty($config['auto_start']) && PHP_SESSION_ACTIVE != session_staus() ) {
            ini_set('session.auto_start', 0);
            $isDOStart = true;  
        }

        if (isset($config['prefix'])) {
            $this->prefix = $config['prefix'];
        }

        if($isDoStart) {
            $this->start();
        } else {
            $this->init = false;
        }

        return $this;
    }

    /**
     * session 设置
     *
     * @param string $name
     * @param mixed $value
     * @param string $prefix 作用域（前缀）
     * @return void
     */
    public function set(string $name, $value, string $prefix = '')
    {
        empty($this->init) && $this->boot();

        $prefix = !empty($prefix) ? $preifx : $this->prefix;

        if( strpos($name, '.') ) {
            // 二维数组赋值
            list($name1, $name2) = explode('.', $name);
            if($prefix) {
                $_SESSION[$prefix][$name1][$name2] = $value;
            } else {
                $_SESSION[$name1][$name2] = $value;
            }
        } else if ($preifx) {
            $_SESSION[$prefix][$name] = $value;
        } else {
            $_SESSION[$name] = $value;
        }

    }

    /**
     * get the value of session
     *
     * @param string $name
     * @param string $prefix
     * @return void
     */
    public function get(string $name = '', string $prefix = '')
    {
        empty($this->init) && $this->boot();

        $prefix = !empty($prefix) ? $preifx : $this->prefix;

        $value = $prefix ? ( $_SESSION[$preifx] ?? [] ) : $_SESSION;

        if('' != $name) {
            $name = explode('.', $name);

            foreach($name as $val) {
                if( isset($value[$val]) ) {
                    $value = $value[$val];
                } else  {
                    $value = null;
                    break;
                }
            }
        }

        return $value;
    }

    /**
     * session start
     *
     * @return void
     */
    public function start()
    {
        session_start();

        $this->init = true;
    }

    /**
     * session 恢复暂停或者初始化
     *
     * @return void
     */
    public function boot()
    {
        // 如果没有初始化，进行初始化
        if( is_null($this->init) ) {
            $this->init();
        }

        // 如果暂停了，并且有session 活动，恢复session, 并且说明session 已经初始化过了 
        if( false === $this->init ) {
            if( PHP_SESSION_ACTIVE != session_status() ) {
                $this->start();
            }
            $this->init = true;
        }
        
    }

    /**
     * pause session
     *
     * @return void
     */
    public function pause()
    {
        session_write_close();
        $this->init = false;
    }

}
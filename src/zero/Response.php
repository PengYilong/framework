<?php
namespace zero;

class Response
{
    /**
     * Application object
     *
     * @var Application
     */
    protected $app;

    /**
     * 
     */
    public $data;

    protected $contentType = 'text/html';

    protected $code = 200;

    protected $charset = 'utf-8';

    protected $header;

    /**
     * Undocumented function
     *
     * @param mixed $data
     * @param int $code
     * @param array $header
     * @param array $options
     */
    public function __construct($data, $code = 200, array $header = [], array $options = [])
    {
        $this->data = $data;
        $this->code = $code;

        $this->contentType($this->contentType, $this->charset);

        $this->app = Container::get('application');
    }

    /**
     * creating a resoponse object
     *
     * @return void
     */
    /**
     * creating a resoponse object
     *
     * @param mixed $data
     * @param string $type
     * @param integer $code
     * @param array $header
     * @param array $options
     * @return void
     */
    public static function create($data, string $type, int $code = 200, array $header = [], $options = [])
    {
        $class = false !== strpos($type, '\\') ? $type : '\\zero\\response\\' . ucfirst(strtolower($type));
        
        if( class_exists($class) ) {
            $res  =  new $class($data, $code, $header, $options);
            return $res;
        }
        
        return new static($data, $code, $header, $options);
    }

    public function send()
    {  
        $data = $this->getContent();
        
        http_response_code($this->code);
        
        if( $this->header ) {
            
            foreach($this->header as $key => $value) {
                header($key . ':' . $value);
            }
        }
       
        $this->sendData($data);
    }

    public function getContent()
    {
        $res = $this->output($this->data);
        
        return $res;
    }

    public function output($data)
    {
        return $data;
    }

    public function sendData($data)
    {
        echo $data;
    }

    public function contentType(string $contentType, string $charset)
    {
        $this->header['Content-Type'] = $contentType . ';' . $charset;
    }

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['app']);

        return $data;
    }
}
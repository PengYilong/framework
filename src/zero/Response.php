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
            return new $class($data, $code, $header, $options);
        }

        return new static($data, $code, $header, $options);
    }

    public function send()
    {   
        $data = $this->getContent();
        $this->sendData($data);
    }

    public function getContent()
    {
        return $this->output($this->data);
    }

    public function output($data)
    {
        return $data;
    }

    public function sendData($data)
    {
        echo $data;
    }

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        uset($data['app']);

        return $data;
    }
}
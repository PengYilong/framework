<?php
namespace zero;

class Response
{
    /**
     * 
     */
    public $data;

    public function __construct($data = '', $code = '')
    {
        $this->data = $data;
    }

    public function send()
    {
        $this->sendData($this->data);
    }

    public function sendData($data)
    {
        echo $data;
    }
}
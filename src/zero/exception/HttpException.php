<?php
namespace zero\exception;

class HttpException extends \RuntimeException
{

    public $statusCode;
    public $headers;

    public function __construct($statusCode, $message = null, array $headers = [], $code = 0 )
    {
        $this->headers = $headers;
        $this->message = $message;
        $this->code = $statusCode;
    }    
}
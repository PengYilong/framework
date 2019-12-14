<?php
namespace zero\exceptions;

class HttpException extends \RuntimeException
{

    public $statusCode;
    public $headers;

    public function __construct($statusCode, $message = NULL, array $headers = [], $code = 0 )
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->message = $message;
    }    
}
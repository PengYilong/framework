<?php
namespace zero\exception;

use zero\Response;

class HttpResponseException extends \RuntimeException
{

    /**
     * @var object response object
     */
    public $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->code = 0;
    }

}
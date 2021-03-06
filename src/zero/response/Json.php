<?php
namespace zero\response;

use zero\Response;

class Json extends Response
{

    protected $contentType = 'application/json';

    public function output($data)
    {
        $res = json_encode($data);
        if(false === $res) {
            throw new \Exception(json_last_error_msg());
        }
        return $res;
    }    

}
<?php
namespace zero\response;

use zero\Response;

class Json extends Response
{

    public function output($data)
    {
        return json_encode($data);
    }    

}
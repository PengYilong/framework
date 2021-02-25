<?php
namespace zero\response;

use zero\Response;

class View extends Response
{

    public function output($data)
    {
        return $this->app['view']->fetch($data);
    }    

}
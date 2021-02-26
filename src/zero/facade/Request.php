<?php
namespace zero\facade;

use zero\Facade;

class Request extends Facade{
   
    public static function getFacadeClass()
    {
        return 'request';
    }
}
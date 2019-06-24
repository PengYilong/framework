<?php
namespace zero\facade;

use zero\Facade;

class Application extends Facade{
   
    public static function getFacadeClass()
    {
        return 'Application';
    }
}
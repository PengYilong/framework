<?php
namespace zero\facade;

use zero\Facade;

class Config extends Facade{
   
    public static function getFacadeClass()
    {
        return 'config';
    }
}
<?php
namespace zero\facade;

use zero\Facade;

class Env extends Facade{
   
    public static function getFacadeClass()
    {
        return 'env';
    }
}
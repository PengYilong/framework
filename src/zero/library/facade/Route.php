<?php
namespace zero\facade;

use zero\Facade;

class Route extends Facade{
   
    public static function getFacadeClass()
    {
        return 'route';
    }
}
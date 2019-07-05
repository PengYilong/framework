<?php
namespace app\api\behavior;

class CheckAuth
{
    public function run($name = 'default')
    {
        return 'CheckAuth->run.('. $name .')';
    }

}

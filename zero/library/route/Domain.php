<?php
namespace zero\route;

use zero\Route;

class Domain extends RuleGroup
{

    /**
     * @var string the name of the domain
     */
    public $name;

    /**
     * @var string|array
     */
    public $rule;

    /**
     * @var object $route
     */
    public $route;

    public function __construct(Route $route, $name = '', $rule = [])
    {
        $this->route = $route;
        $this->name = $name;
        $this->rule = $rule;
    }

}
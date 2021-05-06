<?php
namespace zero\route;

use zero\Route;
use zero\Request;

class Domain extends RuleGroup
{

    /**
     * struct function
     *
     * @param Route $route
     * @param string $name
     * @param mixed $rule
     * @param array $options
     * @param array $pattern
     */
    public function __construct(Route $router, string $name = '', $rule = null)
    {
        $this->router = $router;
        $this->domain = $name;
        $this->rule = $rule;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $url
     * @return void
     */
    public function check(Request $request, string $url, bool $completeMatch = false)
    {
        return parent::check($request, $url);
    }

}
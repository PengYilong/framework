<?php
namespace zero\route;

class RuleGroup extends Rule
{
   public function parseGroupRule($rule)
   {
       $this->route->bind($this->name, $rule);
   } 
}
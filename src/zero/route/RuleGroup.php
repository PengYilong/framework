<?php
namespace zero\route;

class RuleGroup extends Rule
{
   public function parseGroupRule($rule)
   {
       $this->route->bind($this->name, $rule);
   }
   
   public function lazy($lazy = true)
   {
        if(!$lazy){
            $this->parseGroupRule($this->rule);
        }
        return $this;
   }
}
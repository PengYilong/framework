<?php
namespace zero;

class View
{

    public function __construt(Config $config)
    {
        $this->engine($config->pull('template'));
    }

    public function engine($options)
    {
        
        
        return $this;
    }

    public function fetch(string $template = '')
    {
        return $this->engine->fetch($template);
    }

}
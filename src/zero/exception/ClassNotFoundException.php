<?php
namespace zero\exception;

class ClassNotFoundException extends \RuntimeException{

    public $class;

    public function __construct($message, $class)
    {
        $this->message = $message;
        $this->class = $class;
    }
} 
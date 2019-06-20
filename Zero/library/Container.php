<?php
namespace zero;

use ArrayAccess;
use ReflectionClass;

class Container implements ArrayAccess{

    /**
     * @var container
     */
    private static $instance = [];

    /**
     * the classes instantiated
     */
    protected $instances;

    protected $bind = [
        'app' => Application::class,
    ];

    private function __construct()
    {
    }

    /**
     *  
     */
    public static function get($class, $args = [])
    {
       return static::getInstance()->make($class, $args);
    }

    /**
     * 
     */
    public function make($class, $args)
    {
        $realClass = $this->bind[$class] ?? $class;
        try {
            $ref = new ReflectionClass($realClass);
            $constructor = $ref->getConstructor();
            if( $constructor ){
                return $ref->newInstanceArgs($args);
            } else {
                return $ref->newInstance();
            }
             
        } catch (ReflectionException $e) {
            throw ClassNotFoundException('Class Not Found:'. $e->getMessage());
        }
    }

    /**
     *  
     */
    public static function getInstance()
    {
        if( NULL == static::$instance ){
            static::$instance = new static;
        }   
        return static::$instance;
    }

    public function offsetExists ( $offset ) : bool 
    {
        return isset($this->instances[$offset]);
    }

    public function offsetGet( $offset )
    {
        return $this->instances[$offset] ?? NULL;
    } 

    public function offsetSet( $offset, $value) : void
    {
        if( is_null($offset) ){
            $this->instances[] = $value;
        } else {
            $this->instances[$offset] = $value;
        }
    } 

    public function offsetUnset( $offset ) : void
    {
        unset($this->instances[$offset]); 
    } 
}
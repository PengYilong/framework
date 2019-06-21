<?php
namespace zero;

use ArrayAccess;
use ReflectionClass;
use InvalidArgumentException;
use Countable;

class Container implements ArrayAccess, Countable{

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
     * @return new instance 
     */
    public function make($class, $args)
    {
        $realClass = $this->bind[$class] ?? $class;
        try {
            $ref = new ReflectionClass($realClass);
            $constructor = $ref->getConstructor();
            if( $constructor ){
                $params = $constructor->getParameters();
                if( !empty( $params ) ){
                    foreach($params as $key=>$value ){
                        if( isset($args[$key]) ){
                            $realArgs[] = $args[$key];
                        } else if( $value->isDefaultValueAvailable() ){
                            $realArgs[] = $value->getDefaultValue();
                        } else {
                            throw new InvalidArgumentException('The param of the method is missed:'. $value->getName());
                        } 
                    }
                } else {
                    $realArgs = $params;
                }
                return $ref->newInstanceArgs($realArgs);
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

    public function count()
    {
        return count($this->instances);
    }
}
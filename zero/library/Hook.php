<?php
namespace zero;

use Closure;

class Hook
{
    /**
     * @var string the portal of the class
     * 
     */
    public $portal = 'run';

    /**
     * @var array  
     */
    public $tags = [];

    /**
     * @param array $tags
     * @param bool $recursive
     */
    public function set(array $tags, $recursive = true)
    {
        if( $recursive ){
            foreach($tags as $key=>$value){
                $this->add($key, $value);
            }
        } else {
            $this->tags = array_merge($this->tags, $tags);
        } 
    }

    /**
     * @param string @tag 
     * @param string|array|callable $value
     * @return array 
     */
    public function add($tag, $value)
    {
        if( is_array($value) ){
            if( !isset($value['_overlay'])  ){
                if( !isset($this->tags[$tag]) ){
                    $this->tags[$tag] = [];
                } 
                $this->tags[$tag] = array_merge($this->tags[$tag], $value);
            } else {
                unset($value['_overlay']);
                $this->tags[$tag] = $value;
            }
        } else {
            $this->tags[$tag][] = $value; 
        } 
        return $this->tags[$tag]; 
    }

    /**
     * @param string $tag
     */
    public function get($tag = '')
    {
        if(empty($tag)){
            return $this->tag;
        }
        return $this->tags[$tag] ?? false;  
    }

    /**
     *  @param $key
     *  @param $params
     *  @param bool $once whether return data once
     *  @return array 
     */
    public function use($key, $params = [], bool $once = false) : array
    {
        $tags = $this->tags[$key] ?? [];
        $results = [];
        foreach($tags as $key => $value){
            $results[] = $this->execTag($value, $params);
        }
        return $results;
    }

    /**
     * 
     */
    public function execTag($value, $params)
    {
        if( $value instanceof Closure ){
            $callback = $value;
        } else {
            if( is_array($value) ){
                list($value, $method) = $value;
            } else {
                $method = $this->portal; 
            }
            $class = Container::get($value);
            if( method_exists($class, $method) ){
                $callback = [$class, $method];
            } else {
                return 'The '. $method. ' of the '. $value .' doesn\'t exist!';
            } 
        }
        return call_user_func_array($callback, $params);
    }

}
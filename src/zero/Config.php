<?php
namespace zero;

class Config
{

	public $path = [];

	/**
	 * the prefix of the config 
	 */
	public $prefix = 'app';

	/**
	 *
	 */
	public $config = []; 

	public $extension = '';

	public function __construct($path = '' , $extension = '.php')
	{
		$this->path = $path;
		$this->extension = $extension;
	}	

	/**
	 * gets the config of get(), get(app.$name), get(app.)
	 * @var type
	 */	
	public function get( $name = '' )
	{
		if( $name && !strpos($name, '.') ){
			$name = $this->prefix . '.' . $name;
		}

		if( empty($name) ){
			return $this->config;
		}

		if( '.' == substr($name, -1) ) {
			return $this->pull(substr($name, 0, -1));
		}

		$name = explode('.', $name);
			
		if( isset($this->config[$name[0]]) && isset( $this->config[$name[0]][$name[1]] ) ){
			$result = $this->config[$name[0]][$name[1]]; 
		} else {
			$result = [];
		}

		return $result;
	}

	/**
	 * sets $name.$value, array, array, $value
	 * @return $result array 
	 */
	public function set($name, $value = '')
	{
		if( is_string($name) ){
			if( !strpos($name, '.') ) {
				$name = $this->prefix . '.' . $name;
			}
			$name_arr = explode($name, '.', 3);
			if( count($name_arr) == 2 ){
				$this->config[$name_arr[0]][$name_arr[1]] = $value;	
			} else {
				$this->config[$name_arr[0]][$name_arr[1]][$name_arr[2]] = $value; 
			}
			$result = $value;
		} else if( is_array($name) ){
			if( !empty($value) ){
				if( isset($this->config[$value]) ){
					$this->config[$value] = array_merge($this->config[$value], $name);
 				} else {
					$this->config[$value] = $name;
				}

				$result = $this->config[$value];
			} else {
				$result = $this->config = array_merge($this->config, $name);
			}
		}
		return $result;
	}

	public function pull($name)
	{
		return $this->config[$name] ?? [];
	}

	public function loadFile()
	{

	}
}
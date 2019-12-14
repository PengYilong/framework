<?php
namespace zero;

class Env 
{

    /**
     * @var array
     */
    public $data;

   	/**
     * @param string $name e.g. app.path
     * @param string $default
	 */	
	public function get( $name = '', $default = '')
	{
		if( empty($name) ){
			return $this->data;
		}

        $name = strtoupper(str_replace('.', '_', $name));

		if( isset($this->data[$name]) ){
			$result = $this->data[$name]; 
		} else {
			$result = $default;
		}

		return $result;
	}

	/**
	 * sets 
     * @param string|array $name
	 * @return $result array 
	 */
	public function set($name, $value = '')
	{
		if( is_string($name) ){
            $key = strtoupper(str_replace('.', '_', $name));
            $this->data[$key] = $value;
			$result = $value;
		} else if( is_array($name) ){
            $name = array_change_key_case($name, CASE_UPPER);
            foreach($name as $key => $value ){
                if( is_array($value) ){
                    foreach( $value as $k => $val ){
                        $this->data[$key.'_'.strtoupper($k)] = $val;
                    } 
                } else {
                    $this->data[$key] = $value;
                }
            }
            $result = $this->data;
		}
		return $result;
	}

	public function load($file)
	{
        $value = parse_ini_file($file);
		return $this->set($value);
	}  
}
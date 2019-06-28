<?php
namespace zero;

class Register
{

	protected static $objects;

	/*	
	 * sets the class to the tree
	 * @return object
	 */
	public static function set($alias , $object)
	{
		return self::$objects[$alias] = $object;
	}

	/*	
	 * gets the class of the tree
	 * @return object|false 
	 */
	public static function get($alias)
	{
		if ( !isset(self::$objects[$alias]) ){
            return false;
        }
		return self::$objects[$alias];
	}	


	/*	
	 *  cancel the cclas of the tree
	 *
	 */
	public static function _unset($alias)
	{
		unset(self::$objects[$alias]);	
	}	
}
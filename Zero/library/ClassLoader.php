<?php
namespace zero;

use zero\exceptions\ClassNotFoundException;

class ClassLoader
{
	/**
	 * to load class
	 * @var array 
	 * 
	 */
	private static $classMap = [];  

	/**
	 * PSR-4
	 */
	private static $prefixLengthsPsr4 = [];
	private static $prefixDirsPsr4 = [];

	/**
	 * to load dirs
	 */
	private static $fallbackDirsPsr4 = [];

	/**
	 * to load files
	 */
	private static $files = [];

	/**
	 * 
	 */
	private static $composerPath;
	
	/**
	 *  
	 */
	public static function register()
	{
		spl_autoload_register('zero\\ClassLoader::autoload', true, true);
		$rootPath = self::getRootPath();
		self::$composerPath = $rootPath . 'vendor' . DIRECTORY_SEPARATOR . 'composer'.DIRECTORY_SEPARATOR;
		$file = self::$composerPath . 'autoload_static.php';
		if(is_dir(self::$composerPath)){
			if( is_file($file) ){
				require $file;
				$declardedClasses = get_declared_classes();
				$composerClass = array_pop($declardedClasses);
				$properies = ['classMap', 'prefixLengthsPsr4', 'prefixDirsPsr4', 'fallbackDirsPsr4', 'files'];
				foreach ($properies as $value) {
					if( property_exists($composerClass, $value) ){
						self::${$value} = $composerClass::${$value};
					}	
				}
			} else {
				self::registerComposerAutoLoader(self::$composerPath);
			}
		}
		self::loadComposerAutoLoadFiles();
		self::addNameSpace('zero\\', __DIR__);
		self::addNameSpace('zero\\', __DIR__.'22');
		self::addAutoLoadDir($rootPath . 'extend');
	}

	/**
	 * 
	 */
	public static function autoload($class)
	{
		if( $file = self::findFile($class) ){
			include $file;
		} 
	}
	
	/**
	 * 
	 */
	public static function addNameSpace($namespaces, $path = '')
	{
		if( is_array($namespaces) ){
			foreach ($namespaces as $namespace => $path) {
				self::addPsr4($namespace, $path, true);
			}
		} else {
			self::addPsr4($namespaces, $path, true);	
		}
	}

	/**
	 * 
	 * @param string the path of composer 
	 */
	public static function registerComposerAutoLoader($composerPath)
	{
		if( is_file($composerPath.'autoload_files.php') ){
			self::$files = require $composerPath.'autoload_files.php'; 
		}	

		if( is_file($composerPath.'autoload_psr4.php') ){
			$map = require $composerPath . 'autoload_psr4.php';
			foreach($map as $namespace => $path){
				self::addPsr4($namespace, $path);
			}
		}	
	}

	/**
     * Registers a set of PSR-4 directories for a given namespace, either
     * appending or prepending to the ones previously set for this namespace.
     *
     * @param string       $prefix  The prefix/namespace, with trailing '\\'
     * @param array|string $paths   The PSR-4 base directories
     * @param bool         $prepend Whether to prepend the directories
     *
     * @throws \InvalidArgumentException
     */
	public static function addPsr4($prefix, $paths, $prepend =  false)
	{
		if ( !isset(self::$prefixDirsPsr4[$prefix]) ) {
			//whether the prefix is valid
			$length = strlen($prefix);		
			if ( '\\' !== $prefix[$length-1] ) {
				throw new \InvalidArgumentException('A non-empty PSR-4 prefix must end with a namespace separator!');
			}
			self::$prefixLengthsPsr4[$prefix[0]][$prefix] = $length;
			self::$prefixDirsPsr4[$prefix][] = $paths;
		} elseif ( $prepend ) {
			self::$prefixDirsPsr4[$prefix][] = $paths;
		}	
	}

	/**
	 * 
	 */
	public static function findFile($class)
	{
		//loads psr4
		$translatedFile = strtr($class, '\\', DIRECTORY_SEPARATOR).'.php';
		$firstAlphabat = $translatedFile[0];
		$gotNamespace = self::$prefixLengthsPsr4[$firstAlphabat];
		if( isset($gotNamespace) ) {
			foreach( $gotNamespace as $namespace => $length ) {
				if( 0 === strpos($class, $namespace) ){
					foreach ( self::$prefixDirsPsr4[$namespace] as $dir ) {
						$file = $dir.DIRECTORY_SEPARATOR.substr($translatedFile, $length);
						if( is_file( $file ) ) {
							return $file; 
						}
					}
				}
			}
		}

		//loads extra dir
		foreach( self::$fallbackDirsPsr4 as $dir ) {
			$file = $dir.DIRECTORY_SEPARATOR.$translatedFile;
			if( is_file($file) ){
				return $file; 
			}
		}
		return false;
	}

	/**
	 * 
	 */
	public static function getRootPath()
	{
		return realpath(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).DIRECTORY_SEPARATOR;
	}

	/**
	 * 
	 */
	public static function addAutoLoadDir($path)
	{
		self::$fallbackDirsPsr4[] = $path;
	}

	/**
	 * includes specific files  
	 * 
	 */
	public static function loadComposerAutoLoadFiles()
	{
		if( !empty(self::$files) ){
			foreach (self::$files as $value) {
				if( is_file($value) ){
					include $value;
				}
			}
		}
	}

}
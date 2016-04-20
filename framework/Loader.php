<?php
/**
 * Class Loader
 *
 */

class Loader{
    protected static $instance   = null;
    protected static $namespaces = array();


    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /*
     * Method for autoload classes based namespaces
     */
    public static function load($classname){
        $str_pos = strpos ($classname , '\\');
        $sub_str = substr($classname, 0, $str_pos + 1);

        if(isset(self::$namespaces[$sub_str])){
            $path = substr_replace($classname, self::$namespaces[$sub_str], 0, $str_pos);
            $path = str_replace("\\","/", $path) . '.php';

            if(file_exists($path)){
                include_once($path);
            }
        }else{
            $path = str_replace('Framework','',$classname);
            $path = __DIR__ . str_replace("\\","/", $path) . '.php';
            if(file_exists($path)){
                include_once($path);
            }
        }
    }
    
    /**
     * 
     */
    private function __construct(){
        spl_autoload_register(array(__CLASS__, 'load'));
    }
    
    /**
     * 
     */
    private function __clone(){
        // lock
    }

    /**
     * 
     * @param type $name
     * @param type $path
     */
    public static function addNamespacePath($name, $path){
        $path = realpath($path);
        self::$namespaces[$name] = $path;
    }
}
Loader::getInstance();

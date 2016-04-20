<?php
/**
 * Class Service
 *
 * @package Framework\DI\Service
 */

namespace Framework\DI;

class Service {
    protected static $services = array();
    protected static $instances = array();

    /**
     * 
     * @param type $service_name
     * @param \Closure $service
     */
    public static function set($service_name, \Closure $service){
        $service_name = strtolower($service_name);

        static::$services[$service_name] = $service;
    }

    /**
     * 
     * @param type $service_name
     * @return type
     */
    public static function get($service_name){
        $service_name = strtolower($service_name);

        if(!isset(static::$instances[$service_name])){
            if(isset(static::$services[$service_name])){
                $inst = static::$services[$service_name];
                static::$instances[$service_name] = $inst();
            }else{
                static::$instances[$service_name] = null;
            }
        }

        return static::$instances[$service_name];
    }
} 

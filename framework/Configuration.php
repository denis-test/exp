<?php
/**
 * Class Configuration
 *
 * @package Framework
 */


namespace Framework;

class Configuration
{
    protected $configs = array();

    public function __construct($configFilePath = null)
    {
        $this->loadFile($configFilePath);
    }

    /**
     * 
     * @param type $configFilePath
     */
    public function loadFile($configFilePath)
    {
        if(file_exists($configFilePath)) {
            $this->configs = include($configFilePath);
        }
    }

    /**
     * 
     * @param type $name
     * @param type $value
     */
    public function set($name, $value)
    {
        $this->configs[$name] = $value;
    }

    /**
     * 
     * @param type $name
     * @return type
     */
    public function get($name)
    {
        return $this->configs[$name];
    }
} 

<?php
/**
 * Class Router
 *
 * @package Framework\Router
 */

namespace Framework\Router;

use Framework\DI\Service;

class Router{
    /**
     * @var array
     */
    protected static $map = array();
    
    /**
     * Class construct
     */
    public function __construct($routing_map = array()){
        self::$map = (array) $routing_map;
    }
    
    /**
     * Parse URL
     *
     * @param $url
     */
    public function parseRoute($url = ''){
        $route_found = null;
        $method = Service::get('request')->getMethod();
        
        foreach(self::$map as $route){

            $pattern = $this->prepare($route);
            
            if(preg_match($pattern, $url, $params)){
                if (isset($route['_requirements']['_method'])){
                    if ($route['_requirements']['_method'] != $method){
                        continue;
                    }
                }
                
                preg_match($pattern, str_replace(array('{','}'), '', $route['pattern']), $param_names);
                $params = array_map('urldecode', $params);
                $params = array_combine($param_names, $params);
                array_shift($params); // Get rid of 0 element
                $route_found = $route;
                $route_found['params'] = $params;
                break;
            }
        }
        return $route_found;
    }
    
    /**
     * 
     * @param type $route_name
     * @param type $params
     * @return type
     */
    public function getRoute($route_name, $params = array()){ // Было buildRoute
        $route_found = '';
        
        if(isset(self::$map[$route_name])){
            $route = self::$map[$route_name];
            
            preg_match('~\{[\w\d_]+\}~Ui', $route['pattern'], $placeholders);
            if(empty($placeholders)){
                $result = $route['pattern'];
            }else{	
                foreach ($placeholders as $key => $placeholder) {
                    $placeholder = str_replace(array('{','}'), '', $placeholder);

                    if (isset($route['_requirements'][$placeholder])){
                        $pattern = '~^'. $route['_requirements'][$placeholder].'$~';
                    }else{
                        $pattern = '~^[\w\d_]+$~';
                    }

                    if(isset($params[$placeholder]) && preg_match($pattern, $params[$placeholder], $result)){
                        $route_found = str_replace('{'.$placeholder.'}', $params[$placeholder], $route['pattern']);
                    }else{
                        $route_found = '';
                        break;
                    }
                }
            }
        }

        return $result;
    }
    
    /**
     * 
     * @param type $route
     * @return string
     */
    private function prepare($route){
        $pattern = preg_replace('~\{[\w\d_]+\}~Ui','([\w\d_]+)', $route['pattern']);
        $pattern = '~^'. $pattern.'$~';
        return $pattern;
    }
}

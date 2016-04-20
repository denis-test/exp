<?php
/**
 * Class Controller
 * Controller prototype
 *
 * @package Framework\Controller
 */

namespace Framework\Controller;

use Framework\Response\Response;
use Framework\Response\ResponseRedirect;
use Framework\DI\Service;


abstract class Controller {
    protected $pathToModule;
    protected $pathToView;
    protected $controllerName;
    protected $view = 'views';
    protected $viewExtension = '.php';
    
    public function __construct(){
        $reflection = new \ReflectionClass($this);

        $pathParts = pathinfo( $reflection->getFileName() );
        $this->pathToModule = realpath($pathParts['dirname'].DIRECTORY_SEPARATOR.'..');
        $this->controllerName = str_replace('Controller','',$pathParts['filename']);
        $this->pathToView  = $this->pathToModule.DIRECTORY_SEPARATOR;
        $this->pathToView .= $this->view.DIRECTORY_SEPARATOR;
        $this->pathToView .= $this->controllerName;
    }
    
    /**
     * 
     * @param string $layout
     * @param type $data
     * @return Response
     */
    public function render($layout, $data = array()){
        $response = false;
        $layout = $this->pathToView.DIRECTORY_SEPARATOR.$layout.$this->viewExtension;

        if(file_exists($layout)){
            $content = Service::get('renderer')->render($layout, $data);
            $response = new Response($content);
        }

        return $response;
    }
    
    /**
     * 
     * @param type $route
     * @return ResponseRedirect
     */
    public function redirect($route, $flush = '', $flushType = 'success'){
        if ($flush) {
            Service::get('session')->addFlush($flushType, $flush);
        }
        return new ResponseRedirect($route); 
    }
    
    /**
     * 
     * @return type
     */
    public function getRequest(){
        return Service::get('request');
    }
    
    /**
     * 
     * @param type $route
     * @param type $params
     * @return type
     */
    public function generateRoute($route, $params = array()){
        $result  = Service::get('request')->get('url');
        $result .= Service::get('router')->getRoute($route, $params);
        
        return $result;
    }
} 

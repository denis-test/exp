<?php
/**
 * Class Renderer
 * 
 * @package Framework\Renderer
 */

namespace Framework\Renderer;

use Framework\DI\Service;

class Renderer {
    /**
     * @var string  Main wrapper template file location
     */
    protected $main_layout = '';
    protected $helpers     = array();
    protected $data        = array();
    
    /**
     * Class instance constructor
     *
     * @param $main_layout
     */
    public function __construct($main_layout)
    {
        $this->main_layout = $main_layout;

        $this->helpers = array(
            'include' => function ($controller, $action, $params) {
                $controllerReflection = new \ReflectionClass($controller);
                $action = $action.'Action';

                if($controllerReflection->hasMethod($action)){
                    $controller = $controllerReflection->newInstance();
                    $actionReflection = $controllerReflection->getMethod($action);
                    $response = $actionReflection->invokeArgs($controller, $params);

                    $response->sendBody();
                }
            },

            'generateToken' => function() {
                $token = Service::get('security')->getToken();
                echo '<input type="hidden" name="token" value="'.$token.'" />';
            },

            'getRoute' => function($route, $params=array()) {
                return Service::get('router')->getRoute($route, $params);
            }
        );

        if(Service::get('security')->isAuthenticated()){
            $this->data['user'] = Service::get('session')->user;
        }else{
            $this->data['user']  = null;
        }

        $this->data['flush'] = Service::get('session')->getFlush();
    }
    
    /**
     * Render main template with specified content
     *
     * @param $content
     *
     * @return html/text
     */
    public function renderMain($content){
        extract($this->data);
        extract($this->helpers);
        return $this->render($this->main_layout, compact('content'), false);
    }

    /**
     * Render specified template file with data provided
     *
     * @param   string  Template file path (full)
     * @param   mixed   Data array
     * @param   bool    To be wrapped with main template if true
     *
     * @return  text/html
     */
    public function render($template_path, $data = array(), $wrap = true){
        extract($data);
        extract($this->data);
        extract($this->helpers);
        
        ob_start();
        include( $template_path );
        $content = ob_get_clean();

        if($wrap){
            $content = $this->renderMain($content);
        }

        return $content;
    }
} 

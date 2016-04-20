<?php
/**    
 * Application.php
 * 
 */

namespace Framework;

use Framework\Router\Router;
use Framework\Exception\HttpNotFoundException;
use Framework\Exception\AuthRequredException;
use Framework\Exception\BadResponseTypeException;
use Framework\Exception\DatabaseException;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;
use Framework\DI\Service;


class Application
{
    private $response;

    /**
     * 
     * @param type $config
     */
    public function __construct($config = null)
    {
        \Loader::addNamespacePath('CMS\\',__DIR__.'/../src/CMS');

        Service::set('configuration', function(){
                        return new \Framework\Configuration();
                    });

        Service::get('configuration')->loadFile($config);

        Service::set('db', function (){
                        return new \Framework\Connection(Service::get('configuration')->get('pdo'));
                    });
        Service::set('router', function (){
                        return new \Framework\Router\Router(Service::get('configuration')->get('routes'));
                    });
        Service::set('request', function (){
                        return new \Framework\Request\Request();
                    });
        Service::set('security', function (){
                        return new \Framework\Security\Security();
                    });
        Service::set('session', function (){
                        return new \Framework\Session\Session();
                    });
        Service::set('renderer', function (){
                        return new \Framework\Renderer\Renderer(Service::get('configuration')->get('main_layout'));
                    });

        Service::get('session');
    }

    /**
     * 
     * @throws HttpNotFoundException
     * @throws BadResponseTypeException
     */
    public function run()
    {
        $route = Service::get('router')->parseRoute(Service::get('request')->get('uri'));
        
        try{
            if(empty($route)) {
                throw new HttpNotFoundException('Route not found', 404);
            }

            $controllerReflection = new \ReflectionClass($route['controller']);

            $action = $route['action'] . 'Action';

            if($controllerReflection->hasMethod($action)){
                $controller = $controllerReflection->newInstance();
                $actionReflection = $controllerReflection->getMethod($action);
                $this->response = $actionReflection->invokeArgs($controller, $route['params']);

                if($this->response instanceof Response){
                    $this->response->send();
                } else {
                    throw new BadResponseTypeException('Result is not instance of Response');
                }
            }else{
                throw new HttpNotFoundException('The method or controller not found', 404);
            }
        }
        catch(BadResponseTypeException $e){
            $e->getResponse()->send();
        }
        catch(HttpNotFoundException $e){
            $e->getResponse()->send();
        }
        catch(DatabaseException $e){
            echo $e->getMessage();
        }
        catch(AuthRequredException $e){
            $e->getResponse()->send();
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    }
} 

<?php
/**    
 * Session.php
 * 
 */

namespace Framework\Session;

use Framework\DI\Service;

class Session {
    public    $messages = [];
    protected $ttl = 900;
    protected $cookieDomain;
    protected $cookiePath = '/';
    protected $uriHistrSize = 5;
    
    public function __construct(){
        $this->cookieDomain = Service::get('request')->get('host');   
        session_start();
        $this->setUriHistr(Service::get('request')->get('uri'));
    }
    
    /**
     * 
     * @param type $name
     * @param type $val
     */
    public function __set($name, $val){
        if($val === NULL && isset($_SESSION[$name])){
            unset($_SESSION[$name]);
        }
        $_SESSION[$name] = $val;
    }
    
    /**
     * 
     * @param type $name
     * @return type
     */
    public function __get($name){
        return (empty($_SESSION[$name])? null : $_SESSION[$name]);
    }
    
    /**
     * 
     * @param type $type
     * @param type $message
     */
    public function addFlush($type, $message){
        $_SESSION['messages'][$type][] = $message;
    }
    
    /**
     * 
     * @return type
     */
    public function getFlush()
    {
        $result = array();

        if(isset($_SESSION['messages'])) {
            $result = $_SESSION['messages'];
            unset($_SESSION['messages']);
        }

        return $result;
    }
    
    /**
     * 
     * @param type $name
     * @param type $value
     */
    public function setCookie($name, $value = ''){
        setcookie($name, $value, time() + $this->ttl, $this->cookiePath, $this->cookieDomain);
    }
    
    /**
     * 
     * @param type $name
     * @return type
     */
    public function getCookie($name){
        return (isset($_COOKIE[$name]))? $_COOKIE[$name] : '';
    }
    
    /**
     * 
     * @param type $uri
     */
    public function setUriHistr($uri){
        $uriHistr = $this->uriHistr;
       
        $uriHistr[] = $uri;
        
        $uriHistrCount = count($uriHistr);
        
        if($uriHistrCount > $this->uriHistrSize) {
            $uriHistrCount = $uriHistrCount - $this->uriHistrSize;
        
            while ($uriHistrCount) {
                array_shift ($uriHistr);
                $uriHistrCount--;
            }
        }
        
        $this->uriHistr = $uriHistr;
    }
    
    /**
     * 
     * @return type
     */
    public function getUriHistr(){
        $uriHistr = $this->uriHistr;
        
        $result = array_pop($uriHistr);
        
        $this->uriHistr = $uriHistr;
        
        return $result;
    }
    
    /**
     * 
     * @param type $uri
     */
    public function setReturnUrl($uri)
    {
        $returnUrl  = Service::get('request')->get('url');
        
        while ($UriHistr = $this->getUriHistr()) {
            if ($UriHistr != $uri) {
                $returnUrl .= $UriHistr;
                break;
            }
        }
        
        $this->returnUrl = $returnUrl;
    }
}

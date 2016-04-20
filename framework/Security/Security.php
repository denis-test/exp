<?php
/**
 * Class Security
 *
 * @package Framework\Security
 */

namespace Framework\Security;

use Framework\DI\Service;
use Blog\Model\User;

class Security
{
    /**
     * 
     * @return type
     */
    public function isAuthenticated()
    {
        $user = Service::get('session')->user;
        return !empty($user);
    }
    
    /**
     * 
     * @param User $user
     */
    public function setUser(User $user)
    {
        if($this->isTokenValid()){
            Service::get('session')->user = $user;
            
            Service::get('session')->setReturnUrl(Service::get('request')->get('uri'));
        }
    }
    
    /**
     * 
     */
    public function clear()
    {
        Service::get('session')->user = null;
    }
    
    /**
     * 
     * @return type
     */
    public function isTokenValid()
    {
        $postToken    = Service::get('Request')->post('token');
        $cookieToken  = Service::get('session')->getCookie('token');
        $sessionToken = Service::get('session')->token;
        
        $this->generateToken();
        
        return ($postToken == $cookieToken && $postToken == $sessionToken)? true : false;
    }
    
    /**
     * 
     */
    public function generateToken()
    {
        $token = md5(uniqid(mt_rand(),true));
        Service::get('session')->token = $token;
    }
    
    /**
     * 
     * @return type
     */
    public function getToken()
    {
        if (!isset(Service::get('session')->token)){
            $this->generateToken();
        }
        
        $token = Service::get('session')->token;
        
        Service::get('session')->setCookie('token', $token);
        return $token;
    }
    
    /**
     * 
     * @param type $password
     * @return type
     */
    public function hashPassword($password)
    {
        return md5($password);
    }
}

<?php
/**    
 * Request.php
 * 
 */

namespace Framework\Request;

use Framework\DI\Service;

class Request
{
    protected $host;
    protected $uri;
    protected $url;
    protected $scheme = 'http';
    protected $method;
    protected $referer;
    protected $protocol;
    protected $WiteList = array(
        'image/png'=>'.png',
        'image/jpeg'=>'.jpg',
        'image/gif'=>'.gif'
        );
    protected $uploaddir = '/uploads/';

    /**
     * 
     */
    public function __construct()
    {
        $this->host     = $this->filter($_SERVER['SERVER_NAME'], 'HOST');
        $this->uri      = $this->filter($_SERVER['REQUEST_URI'], 'URI');
        $this->method   = $this->filter($_SERVER['REQUEST_METHOD'], 'WORD');
        $this->protocol = $this->filter($_SERVER['SERVER_PROTOCOL'], 'RAW');
        $this->url      = $this->scheme.'://'.$this->host;
    }

    /**
     * 
     * @return type
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * 
     * @return type
     */
    public function isPost()
    {
        return ($this->getMethod()=='POST');
    }

    /**
     * 
     * @return type
     */
    public function isGet()
    {
        return ($this->getMethod()=='GET');
    }

    /**
     * 
     * @param type $header
     * @return type
     */
    public function getHeaders($header = null)
    {
        $data = apache_request_headers();
        if(!empty($header)){
            $data = array_key_exists($header, $data) ? $data[$header] : null;
        }
        return $data;
    }

    /**
     * 
     * @param type $name
     * @param type $default
     * @return type
     */
    public function get($name, $default = '')
    {
        return (isset($this->$name)? $this->$name : $default);
    }

    /**
     * 
     * @param type $varname
     * @param type $filter
     * @return type
     */
    public function post($varname = '', $filter = 'STRING')
    {
        $result = false;

        if( array_key_exists($varname, $_POST) ){
            $result = $this->filter($_POST[$varname], $filter);
        }

        if($result && $varname == 'password'){
            $result = Service::get('security')->hashPassword($result);
        }

        return $result;
    }

    /**
     * 
     * @param type $value
     * @param type $filter
     * @return type
     */
    protected function filter($value, $filter = 'STRING')
    {
        switch (strtoupper($filter))
        {
            case 'INT':
            case 'INTEGER':
                $pattern = '/[-+]?[0-9]+/';
                preg_match($pattern, (string) $value, $matches);
                $result = isset($matches[0]) ? (int) $matches[0] : 0;

                break;
            case 'FLOAT':
            case 'DOUBLE':
                $pattern = '/[-+]?[0-9]+(\.[0-9]+)?([eE][-+]?[0-9]+)?/';
                preg_match($pattern, (string) $value, $matches);
                $result = isset($matches[0]) ? (float) $matches[0] : 0;

                break;
            case 'BOOL':
            case 'BOOLEAN':
                $result = (bool) $value;

                break;
            case 'WORD':
                $pattern = '/[^A-Z_]/i';
                $result = (string) preg_replace($pattern, '', $value);

                break;
            case 'ALNUM':
                $pattern = '/[^A-Z0-9]/i';
                $result = (string) preg_replace($pattern, '', $value);

                break;
            case 'STRING':
                $result = filter_var($value, FILTER_SANITIZE_STRING);

                break;
            case 'USERNAME':
                $pattern = '/[\x00-\x1F\x7F<>"\'%&]/';
                $result = (string) preg_replace($pattern, '', $value);

                break;
            case 'RAW':
                $result = $value;

                break;
            case 'HOST':
                $pattern = '#^[^-\._][a-z\d_\.-]+\.[a-z]{2,6}$#i';
                $options = array('options' => array('regexp' => $pattern));
                $result = filter_var($value, FILTER_VALIDATE_REGEXP, $options);

                break;
            case 'URL':
                $result = filter_var($value, FILTER_VALIDATE_URL);

                break;
            case 'URI':
                $pattern = '#[^A-Z0-9_/]#i';
                $result = (string) preg_replace($pattern, '', $value);

                break;
            default:
                $result = filter_var($value, FILTER_SANITIZE_STRING);

                break;
        }
        return $result;
    }
}

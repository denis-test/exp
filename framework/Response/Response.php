<?php
/**    
 * Response.php
 * 
 */

namespace Framework\Response;

use Framework\DI\Service;

class Response {
    protected $headers = array();
    public $code = 200;
    public $content = '';
    public $type = 'text/html';
    private static $msgs = array(
        200 => 'Ok',
        302 => 'Found',
        307 => 'Temporary Redirect',
        404 => 'Not found',
        500 => 'Internal Server Error'
    );

    /**
     * 
     * @param type $content
     * @param type $type
     * @param type $code
     */
    public function __construct($content = '', $type = 'text/html', $code = 200){
        $this->code = $code;
        $this->content = $content;
        $this->type = $type;
        $this->setHeader('Content-Type', $this->type);
    }

    /**
     * 
     * @param type $code
     */
    public function setCode($code){
        $this->code = $code;
    }

    /**
     * 
     * @param type $name
     * @param type $value
     */
    public function setHeader($name, $value){
        $this->headers[$name] = $value;
    }

    /**
     * 
     */
    public function sendHeaders(){
        header(Service::get('request')->get('protocol').' '.$this->code.' '.self::$msgs[$this->code]);

        foreach($this->headers as $key => $value){
            header(sprintf("%s: %s", $key, $value));
        }
    }

    /**
     * 
     */
    public function sendBody(){
        echo $this->content;
    }

    /**
     * 
     */
    public function send(){
        $this->sendHeaders();
        $this->sendBody();
    }
} 

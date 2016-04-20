<?php
/**    
 * Response.php
 * 
 */

namespace Framework\Response;

class ResponseRedirect extends Response {
    public function __construct($url, $code = 302)
    {
	$this->code = $code;
        $this->setHeader('Location', $url);
        
        return $this;
    }
} 

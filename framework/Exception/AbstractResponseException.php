<?php
namespace Framework\Exception;

use Framework\Response\Response;
use Framework\DI\Service;
/**    
 * AbstractResponseException.php
 */

abstract class AbstractResponseException extends \Exception
{
	protected $message;
	protected $code;
	
	public function __construct($message, $code = 500)
	{
		//$this->message = $message;
		$this->code = $code;
		parent::__construct($message);
	}
	
	public function getResponse()
	{
		$layout = Service::get('configuration')->get('error_500');
		        
		$content = Service::get('renderer')->render($layout, array('message' => $this->getMessage(), 'code'=>$this->code ) );
		return  new Response($content);
	}
}

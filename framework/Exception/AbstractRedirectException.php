<?php
namespace Framework\Exception;

use Framework\Response\ResponseRedirect;
use Framework\DI\Service;
/**    
 * AbstractRedirectException.php
 */

abstract class AbstractRedirectException extends \Exception
{
	protected $route;
	protected $code;
	
	public function __construct($route, $code = 307)
	{
		$this->route = $route;
		$this->code = $code;
		parent::__construct();
	}
	
	public function getResponse()
	{
		return  $response = new ResponseRedirect(Service::get('router')->getRoute($this->route), $this->code);
	}
}


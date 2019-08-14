<?php

namespace Base;

/**
 * 
 */
use Base\IRequest;

class Routes
{
	private $request; //the request object
	private $supportedHttpMethods = [ 'GET','POST'];
	private $homeUri;	

  /**
  *@param IRequest $request
  *@return void
  */
	function __construct(IRequest $request)
	{
		$this->request = $request;
		$this->homeUri = '/'.ltrim(rtrim(strtolower(TEST_DIR), '/'), '/').'/';
	}

  /**
  *this is a magic method, it invokes a call to a HTTP method and maps the route to method that will handle it.
  *@param string $name
  *@param array $args
  *@return invalidMethodHandler|void
  */
	public function __call($name, $args)
	{
		list($route, $method) = $args;
		
		if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {

			return $this->invalidMethodHandler();
		}

		$this->{strtolower($name)}[$this->formatRoute($route)] = $method;
	}

  /**
  * this formats the route string, cutting off the name of the directory from the URI in development and index.php string in production
  *@param string $route
  *@return string
  */
	private function formatRoute($route)
  	{
  		$route = strtolower($route);
  		$leftTrimmedRoute = ltrim($route, '/');
  		if (strpos($leftTrimmedRoute, '?')>0 || strpos($leftTrimmedRoute, '?')===0) {
  			$route = substr($leftTrimmedRoute, 0, strpos($leftTrimmedRoute, '?'));
  		}
  		$route = rtrim($route, '/'); //this line and the next are for uniformity, to ensure that whether a server prefers trailing slashes or not, our code will always work.
  		$route.='/';
  		$route = preg_replace('_'.$this->homeUri.'_', '', $route, 1);
	    $result = rtrim($route, '/');
	    $result = ltrim($result, '/');
	    if ($result === '')
	    {
	      return '/';
	    }
	    return $result;
  	}

  /**
  *
  * adds a 405 header to the response
  */
	private function invalidMethodHandler()
  {

	    header("{$this->request->serverProtocol} 405 Method Not Allowed");
	}

  /**
  * adds a 404 header to the response
  */
	private function defaultRequestHandler()
	{
	    header("{$this->request->serverProtocol} 404 Not Found");
  }
 	
  /**
  * adds a 500 header to the response
  */
 	private function internalServerError()
 	{
 		header($_SERVER['SERVER_PROTOCOL'].'500 Internal Server Error', true, 500);
 	}

  /**
  * this method resolves a route called to a method defined for the route
  */
  public function resolve()
  {
  		if(!isset($this->{strtolower($this->request->requestMethod)}))
  		{
  			return $this->invalidMethodHandler();
  		}
    	$methodDictionary = $this->{strtolower($this->request->requestMethod)};
    	$formatedRoute = $this->formatRoute($this->request->requestUri);
    	if (!isset($methodDictionary[$formatedRoute])) {
    		return DEV ? raiseError("The route $formatedRoute has no  handler") : $this->defaultRequestHandler();
    	}
    	$method = $methodDictionary[$formatedRoute];
    	if(is_string($method))
    	{
    		return $this->controllerMap($method);
    	}

    	if($method instanceof \Closure){
    		echo call_user_func_array($method, array($this->request));
    	}

    	if((!$method instanceof \Closure) && !is_string($method))
    	{
    		return DEV? raiseError("The route $formatedRoute has an error in the handler set for it") : $this->internalServerError();
    	}
  }

  /**
  *this maps a route to a method and instance of a controller class
  *@param string $method
  */
  private function controllerMap($method)
  {
  	$method = ltrim($method, '/');
  	$method = ltrim($method, '\\');
  	$atPosition = strpos($method, '@');
  	if ($atPosition < 1) {
  		return DEV? raiseError("the route handler $method has @ at the wrong place or not at all") : $this->internalServerError();
  	}
  	$classPath = substr($method, 0, $atPosition);
  	$classMethod = substr($method, ($atPosition+1));
  	if (!class_exists(CONTROLLER_NS.$classPath)) {
  		return DEV? raiseError("you assigned a non existent controller $classPath to handle your route") : $this->internalServerError();
  	}
  	$class = CONTROLLER_NS.$classPath;
    $reflector = new \ReflectionClass($class);
  	$controller = $reflector->isInstantiable()? new $class() : $class;
  	if(!method_exists($controller, $classMethod))
  	{
  		return DEV ? raiseError("The method $classMethod does not exist in class $class") : $this->internalServerError();
  	}

  	echo call_user_func_array([$controller, $classMethod], array($this->request));
  }

  function __destruct()
  {
    $this->resolve();
  }
	
}
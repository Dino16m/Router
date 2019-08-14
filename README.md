# Router
A light weight php routing system 

Every route implements a route and a handler method (function) which could be a closure or a method of a controller class

Directories include
	classes
		default classes, no need to modify them
	controllers
		user defined controller classes, namespaced
	routes
		contains routes.php file which contains user defined routes, defined as so

		$router->HTTPverb('route/name', Closure) for routes handled by anonymous functions
		$router->HTTPver('route /name', 'controllerclassname@method') for routes handled by methods of any controller class in the controller namespace

	Currently supported HTTPverbs include
		POST
		GET
Classes to use include
	Request (implements interface iRequest)
		handles the requests sent to the routes
			methods
				$request->get('GET parameter') returns the value of a get parameter if it exists, returns null otherwise
				$request->input('POST parameter') returns the value of a Post parameter if it exists, returns null otherwise
				$request->__get(postparameter) magic method which returns the value of a post parameter such that $request->postparameter is possible
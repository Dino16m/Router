<?php
namespace Base;

use Controllers\baseController;

class homeController  extends baseController
{
	public function index($request)
	{
		return $request->get('me');
	}
} 

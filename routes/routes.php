<?php 

$router->get('makaa', function($request)
{
	echo $request->get('me') . '<br>';


	
});

$router->get('her', 'homeController@index');
?>






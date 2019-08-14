<?php

	spl_autoload_register(function ($classname){
		$basepath = __DIR__;
		$filename = $basepath . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
		include $filename;
	});
?>
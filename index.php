<?php 
require __DIR__.DIRECTORY_SEPARATOR.'Vendor'.DIRECTORY_SEPARATOR.'autoload.php';
require 'config.php';
if (!defined('DEV')) {
	define('DEV', false);	
}
require __DIR__.DIRECTORY_SEPARATOR.'Vendor'.DIRECTORY_SEPARATOR.'Base'.DIRECTORY_SEPARATOR."errors.php";
if (!defined('URI')) {
	raiseError('URI not defined');
}
if (!defined('TEST_DIR')) {
	define('TEST_DIR', 'index.php');
}
if (!defined('BASE_DIR')) {
	define('BASE_DIR', __DIR__);
}
if (!defined('CONTROLLER_NS')) {
	define('CONTROLLER_NS', "\Controllers\\");
}


$request = new Base\Request();
$router = new Base\Routes($request);
require 'routes'.DIRECTORY_SEPARATOR.'routes.php';

?>
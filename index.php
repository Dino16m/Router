<?php 
const URI = 'Http://localhost/router/';
const TEST_DIR = 'ROUTER/index.php';
Const BASE_DIR = __DIR__;
define('CONTROLLER_NS', "\controllers\\");
define('DEV', true);
require 'autoload.php';
require 'classes'.DIRECTORY_SEPARATOR."errors.php";



$request = new classes\Request();
$router = new classes\Routes($request);
require 'routes'.DIRECTORY_SEPARATOR.'routes.php';

?>
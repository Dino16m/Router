<?php
// handles errors in the mini framework, it displays standard php error messages while in development and a 500 while in production

$error_handler= "handleErrors";
function handleErrors($errno, $errstr, $errfile, $errline)
{
	if (!(error_reporting() & $errno)) {
			return false;
	}


	switch ($errno) {
		case E_WARNING:
			header($_SERVER['SERVER_PROTOCOL'].'500 Internal Server Error', true, 500);
			break;
		case E_STRICT:
			header($_SERVER['SERVER_PROTOCOL'].'500 Internal Server Error', true, 500);
			break;
		case E_USER_NOTICE:
			header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error', true, 500);
			break;
		default:
			die('an error occured');
			break;
	}
	die();

}

if(!DEV)
{
	set_error_handler($error_handler, E_WARNING);
	set_error_handler($error_handler, E_STRICT);
	set_error_handler($error_handler, E_USER_NOTICE );
}

function raiseError($errorMessage)
{
	trigger_error($errorMessage, E_USER_NOTICE);
}

?>
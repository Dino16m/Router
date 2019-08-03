<?php

namespace classes;

/**
* Interface inherited by the Request class
*/
interface IRequest
{
	public function input($key);
	public function get($key);
	public function bootstrapSelf();
}
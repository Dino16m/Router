<?php

namespace  classes;

class Request implements IRequest
{
	private $postArr; //the array containing the values for POST values
	private $getArr; // the array containing the values for GET values

	function __construct()
	{
		$this->bootstrapSelf();
	}

	/**
	* return (void)
	*/
	public function bootstrapSelf()
	{
		foreach ($_SERVER as $key => $value) {
			$this->{$this->toCamelCase($key)}=$value;
		}

	}

	/**
	*converts a snake case string supplied to it to a camel case string
	*@param string $key
	*@return string
	*/
	public function toCamelCase($key) : string
	{
		$result = strtolower($key);
		preg_match_all('/_[a-z]/', $result, $matches);

		foreach ($matches[0] as $match) {
			$c = str_replace('_', '',strtoupper($match));
			$result = str_replace($match, $c, $result);
		}

		return $result;
	}

	/**
	*returns a value matching the supplied key in the internal get array getArr or returns null if not found.
	*it also calls the setGet method if the getArr has not been set.
	*@param string $key
	*@return string|null
	*/
	public function get($key)
	{
		$key = strtolower($key);
		if(!isset($this->getArr)){
			$this->setGet();
		}
		return isset($this->getArr[$key]) ? $this->getArr[$key] : null;
	}

	/**
	* returns a value matching the supplied key in the internal post array postArr or returns null if not found.
	* it also calls the setPost method if the postArr has not been set.
	* @param string $key
	* @return string|null
	*/
	public function input($key)
	{
		$key = strtolower($key);
		if(!isset($this->postArr)){
			$this->setPost();
		}
		return isset($this->postArr[$key]) ? $this->postArr[$key] : null;
	}

	/**
	*sets the value of the postArr array if it hasn't been set.
	*/
	private function setPost()
	{
		$this->postArr = array();
		foreach ($_POST as $key => $value) {
			$this->postArr[strtolower($key)]= filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
		}
	}

	/**
	* sets the value of the getArr array if it hasn't been set
	*/
	private function setGet()
	{
		$this->getArr = array();
		foreach ($_GET as $key => $value) {
			$this->getArr[strtolower($key)]= filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
		}
	}

	/**
	* this is an invokable method which calls the input method under the hood.
	* @param string $name 
	* @return string|null
	*/
	public function __get($name)
	{
		if(!isset($this->postArr)){
			$this->setPost();
		}
		if(!array_key_exists(strtolower($name), $this->postArr))
		{
			return null;
		}
		return $this->input($name);
	}
}
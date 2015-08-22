<?php

/*
 * This file is part of Huxtable\Web
 */
namespace Huxtable\Web;

class Route
{
	/**
	 * @var mixed
	 */
	protected $closure;

	/**
	 * @var string
	 */
	protected $pattern;

	/**
	 * @var Huxtable\Web\Request
	 */
	public $request;

	/**
	 * @var array
	 */
	protected $requiredArguments=[];

	/**
	 * @var array
	 */
	protected $requiredHeaders=[];

	/**
	 * @var Huxtable\Web\Response
	 */
	public $response;

	/**
	 * @param	string	$pattern	URL pattern to match
	 * @param	mixed	$closure	Closure object, name of static function or [object, function] array
	 * @return	void
	 */
	public function __construct( $pattern, $closure )
	{
		$this->pattern = $pattern;
		$this->response = new Response;
		$this->setClosure( $closure );
	}

	/**
	 * @return	\Closure
	 */
	public function getClosure()
	{
		return $this->closure;
	}

	/**
	 * @return	string
	 */
	public function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @return	array
	 */
	public function getRequiredArguments()
	{
		return $this->requiredArguments;
	}

	/**
	 * @return	array
	 */
	public function getRequiredHeaders()
	{
		return $this->requiredHeaders;
	}

	/**
	 * @param	string	$name	Name of argument to require
	 * @return	self			For chaining
	 */
	public function requireArgument( $name )
	{
		$this->requiredArguments[] = $name;
		return $this;
	}

	/**
	 * @param	string	$name	Name of header to require
	 * @return	self			For chaining
	 */
	public function requireHeader( $name )
	{
		$this->requiredHeaders[] = $name;
		return $this;
	}

	/**
	 * @param	mixed	$closure
	 */
	public function setClosure( $closure )
	{
		if($closure instanceof \Closure)
		{
			$this->closure = $closure->bindTo($this);
			return;
		}
	}

	/**
	 * @param	string	$contentType
	 * @return	self	For chaining
	 */
	public function setContentType( $contentType )
	{
		$this->response->setContentType( $contentType );
		return $this;
	}

	/**
	 * @param	Huxtable\Web\Request	$request
	 */
	public function setRequest( Request $request )
	{
		$this->request = $request;
	}
}

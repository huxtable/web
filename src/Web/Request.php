<?php

/*
 * This file is part of Huxtable\Web
 */
namespace Huxtable\Web;

class Request
{
	/**
	 * @param	string	$name	Name of argument
	 */
	public function getArgument( $name )
	{
		$arguments = array_merge( $_GET, $_POST );

		if( isset( $arguments[ $name ] ) )
		{
			return $arguments[ $name ];
		}

		return false;
	}

	/**
	 * @return	array
	 */
	public function getArguments()
	{
		return array_merge( $_GET, $_POST );
	}

	/**
	 * @return	string
	 */
	public function getHost()
	{
		return $this->getHeader( 'HOST' );
	}

	/**
	 * @return	void
	 */
	public function getMethod()
	{
		return $this->getServerVariable( 'REQUEST_METHOD' );
	}

	/**
	 * @param	string	$header		Name of header
	 * @return	string|false
	 */
	public function getHeader( $header )
	{
		$headerNormalized = strtoupper( $header );
		$headerNormalized = str_replace( '-', '_', $headerNormalized );
		$headerNormalized = "HTTP_{$headerNormalized}";

		if( isset( $_SERVER[ $headerNormalized ] ) )
		{
			return $_SERVER[ $headerNormalized ];
		}

		return false;
	}

	/**
	 * @return	string
	 */
	public function getProtocol()
	{
		return strtolower( substr( $this->getServerVariable( 'SERVER_PROTOCOL' ) , 0, strpos( $this->getServerVariable( 'SERVER_PROTOCOL' ), '/' ) ) ) .'://';
	}

	/**
	 * @param	string	$varname	The variable name (ex., 'HTTP_USER_AGENT')
	 * @return	string|false
	 */
	protected function getServerVariable( $varname )
	{
		if( isset( $_SERVER[ $varname ] ) )
		{
			return $_SERVER[ $varname ];
		}
		
		return false;
	}

	/**
	 * @return	string|false
	 */
	public function getURL()
	{
		return $this->getServerVariable( 'PHP_SELF' );
	}

	/**
	 * @return	string|false
	 */
	public function getUserAgent()
	{
		return $this->getServerVariable( 'HTTP_USER_AGENT' );
	}
}

?>

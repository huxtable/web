<?php

/*
 * This file is part of Huxtable\Web
 */
namespace Huxtable\Web;

class Response
{
	/**
	 * @var string
	 */
	protected $contents='';

	/**
	 * @var array
	 */
	protected $headers=[];

	/**
	 * @var int
	 */
	protected $statusCode=200;

	/**
	 * Taken from https://en.wikipedia.org/wiki/List_of_HTTP_status_codes on Aug 20, 2015
	 * @var array
	 */
	protected $statuses =
	[
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Switch Proxy',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		308 => 'Resume Incomplete',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Payload Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		419 => 'Authentication Timeout',
		420 => 'Method Failure',
		421 => 'Misdirected Request',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		426 => 'Upgrade Required',
		449 => 'Retry With',
		450 => 'Blocked by Windows Parental Controls',
		451 => 'Unavailable For Legal Reasons',
		498 => 'Token expired/invalid',
		499 => 'Client Closed Request',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'Loop Detected',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
		520 => 'Unknown Error',
		522 => 'Origin Connection Time-out',
		598 => 'Network read timeout error',
		599 => 'Network connect timeout error'
	];

	/**
	 * @return	void
	 */
	public function __construct()
	{
		$this->setHeader( 'Content-Type', 'text/html' );
	}

	/**
	 * @return	string
	 */
	public function getContents()
	{
		return $this->contents;
	}

	/**
	 * @return	string
	 */
	public function getContentType()
	{
		$contentType = isset( $this->headers[ 'Content-Type' ] ) ? $this->headers[ 'Content-Type' ] : 'text/html';
		return $contentType;
	}

	/**
	 * @return	array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * @return	void
	 */
	public function sendHeaders()
	{
		foreach( $this->getHeaders() as $name => $value )
		{
			header( "{$name}: {$value}" );
		}
	}

	/**
	 * @return	string
	 */
	public function sendStatus()
	{
		$header = sprintf( 'HTTP/1.1 %s %s', $this->statusCode, $this->statuses[ $this->statusCode ] );
		header( $header );
	}

	/**
	 * @param	string	$contents
	 */
	public function setContents( $contents )
	{
		$this->contents = $contents;
	}

	/**
	 * @param	string	$contentType
	 */
	public function setContentType( $contentType )
	{
		$this->setHeader( 'Content-Type', $contentType );
	}

	/**
	 * @param	string	$name	Name of header
	 * @param	string	$value	Value of header
	 */
	public function setHeader( $name, $value )
	{
		$this->headers[ $name ] = $value;
	}

	/**
	 * @param	int	$code
	 */
	public function setStatusCode( $statusCode )
	{
		if( isset( $this->statuses[ $statusCode ] ) )
		{
			$this->statusCode = $statusCode;
		}
		else
		{
			$this->statusCode = 500;
		}
	}
}

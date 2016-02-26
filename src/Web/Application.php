<?php

/*
 * This file is part of Huxtable\Web
 */
namespace Huxtable\Web;

class Application
{
	/**
	 * @var array
	 */
	public $routes=[];

	/**
	 * Shorthand method for registering DELETE routes
	 * @return	Huxtable\Web\Response
	 */
	public function delete( $pattern, $closure )
	{
		$route = new Route( $pattern, $closure );
		return $this->registerRoute( $route, 'DELETE' );
	}

	/**
	 * Shorthand method for registering GET routes
	 * @return	Huxtable\Web\Response
	 */
	public function get( $pattern, $closure )
	{
		$route = new Route( $pattern, $closure );
		return $this->registerRoute( $route, 'GET' );
	}

	/**
	 * Shorthand method for registering POST routes
	 * @return	Huxtable\Web\Response
	 */
	public function post( $pattern, $closure )
	{
		$route = new Route( $pattern, $closure );
		return $this->registerRoute( $route, 'POST' );
	}

	/**
	 * Shorthand method for registering PUT routes
	 * @return	Huxtable\Web\Response
	 */
	public function put( $pattern, $closure )
	{
		$route = new Route( $pattern, $closure );
		return $this->registerRoute( $route, 'PUT' );
	}

	/**
	 * @param	Huxtable\Web\Route		$route
	 * @param	string					$httpMethod
	 * @return	Huxtable\Web\Route		A reference to the Route object, for chaining
	 */
	public function &registerRoute( Route $route, $httpMethod )
	{
		$pattern = $route->getPattern();

		$this->routes[ $pattern ][ $httpMethod ] = $route;

		return $this->routes[ $pattern ][ $httpMethod ];
	}

	/**
	 * @param	Huxtable\Web\Request	$request
	 * @return	Huxtable\Web\Response
	 */
	public function route( Request $request )
	{
		$url = $request->getURL();
		$method = $request->getMethod();

		// Default response
		$response = new Response;
		$response->setStatusCode( 404 );
		$response->setContents( 'Not Found :(' );

		if( isset( $this->routes[ $url ] ) )
		{
			$routes = $this->routes[ $url ];

			// Route pattern + method match found
			if( isset( $this->routes[ $url ][ $method ] ) )
			{
				$routeObject = $this->routes[ $url ][ $method ];
				$routeObject->setRequest( $request );

				// Call authenticatiion callback if defined
				$authenticationClosure = $routeObject->getAuthenticationClosure();

				if( $authenticationClosure != false )
				{
					try
					{
						call_user_func( $authenticationClosure );
					}
					catch( Request\UnauthorizedRequestException $e )
					{
						$routeObject->response->setStatusCode( 401 );
						$routeObject->response->setContents( $routeObject->response->getStatusMessage() );
						return $routeObject->response;
					}
				}

				// Confirm required headers were sent
				foreach( $routeObject->getRequiredHeaders() as $header )
				{
					if( $request->getHeader( $header ) === false )
					{
						$response->setStatusCode( 400 );
						$response->setContents( $routeObject->response->getContents() );

						return $response;
					}
				}

				// Confirm required arguments were passed
				foreach( $routeObject->getRequiredArguments() as $argument )
				{
					if( $request->getArgument( $argument ) === false )
					{
						$response = new Response;
						$response->setStatusCode( 400 );
						$response->setContents( "Missing required argument '{$argument}'" );

						return $response;
					}
				}

				try
				{
					// Call the main routing closure
					$contents = call_user_func( $routeObject->getClosure() );
				}
				catch( \Exception $e )
				{
					$response = new Response;
					$response->setStatusCode( 500 );
					$response->setContents( $e->getMessage() );

					return $response;
				}

				$routeObject->response->setContents( $contents );
				return $routeObject->response;
			}

			// No route pattern + method match found
			$response->setStatusCode( 405 );
			$response->setContents( 'Method Not Allowed :(' );
		}

		return $response;
	}
}

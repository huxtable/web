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
	 * @param	Huxtable\Web\Route		$route
	 * @param	string					$httpMethod
	 * @return	Huxtable\Web\Route		A reference to the Route object, for chaining
	 */
	public function &registerRoute( Route $route, $httpMethod )
	{
		$pattern = $route->getPattern();

		$this->routes[ $httpMethod ][ $pattern ] = $route;

		return $this->routes[ $httpMethod ][ $pattern ];
	}

	/**
	 * @param	Huxtable\Web\Request	$request
	 * @return	Huxtable\Web\Response
	 */
	public function route( Request $request )
	{
		$method = $request->getMethod();

		if( isset( $this->routes[ $method ] ) )
		{
			$routes = $this->routes[ $method ];

			foreach( $routes as $routePattern => $routeObject )
			{
				if( $routePattern == $request->getURL() )
				{
					$routeObject->setRequest( $request );

					foreach( $routeObject->getRequiredHeaders() as $header )
					{
						if( $request->getHeader( $header ) === false )
						{
							$response = new Response;
							$response->setStatusCode( 400 );
					
							return $response;
						}
					}

					foreach( $routeObject->getRequiredArguments() as $argument )
					{
						if( $request->getArgument( $argument ) === false )
						{
							$response = new Response;
							$response->setStatusCode( 400 );

							return $response;
						}
					}

					$routeObject->response->setContents( call_user_func( $routeObject->getClosure() ) );
					return $routeObject->response;
				}
			}
		}

		$response = new Response;
		$response->setStatusCode( 404 );
		$response->setContents( 'Not Found' );

		return $response;



		return;
		foreach( $this->routes as $pattern => $routeProperties )
		{
			if( $request->getMethod() == strtoupper( $routeProperties['method'] ) )
			{
				// @todo	Implement actual pattern matching
				if( $pattern == $request->getURL() )
				{
					$route = $routeProperties['route'];
					$route->setRequest( $request );



					$route->response->setContents( call_user_func( $route->getClosure() ) );
					return $route->response;
				}
			}
		}

		$response = new Response;
		$response->setStatusCode( 404 );
		$response->setContents( 'Not Found' );

		return $response;
	}
}

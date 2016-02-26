<?php

/*
 * This file is part of Huxtable\Web
 */
namespace Huxtable\Web;

use \Huxtable\Core\FileInfo;

class Router
{
	/**
	 * @param
	 * @return	void
	 */
	public function __construct( FileInfo $dirRoutes )
	{
		$this->dirRoutes = $dirRoutes;
	}

	/**
	 * @return	array
	 */
	public function getRoutes( FileInfo $dirRoutes=null )
	{
		$routes = [];

		if( is_null( $dirRoutes ) )
		{
			$dirRoutes = $this->dirRoutes;
		}

		$routeFiles = $dirRoutes->children( ['.DS_Store'] );

		foreach( $routeFiles as $routeFile )
		{
			if( $routeFile->isDir() )
			{
				// Descent into subfolders
				$routes = array_merge( $routes, $this->getRoutes( $routeFile ) );
			}
			else
			{
				$routes[] = $routeFile;
			}
		}

		return $routes;
	}
}

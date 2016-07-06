<?php

/*
 * This file is part of Huxtable\Web
 */
namespace Huxtable\Web;

use Huxtable\Core\File;

class Router
{
	/**
	 * @param	Huxtable\Core\File\Directory	$dirRoutes
	 * @return	void
	 */
	public function __construct( File\Directory $dirRoutes )
	{
		$this->dirRoutes = $dirRoutes;
	}

	/**
	 * @param	Huxtable\Core\File\Directory	$dirRoutes
	 * @return	array
	 */
	public function getRoutes( File\Directory $dirRoutes=null )
	{
		$routes = [];

		if( is_null( $dirRoutes ) )
		{
			$dirRoutes = $this->dirRoutes;
		}

		$routeFiles = $dirRoutes->children();

		foreach( $routeFiles as $routeFile )
		{
			if( $routeFile->isDir() )
			{
				// Descend into subfolders
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

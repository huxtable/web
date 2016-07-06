<?php

/*
 * This file is part of Huxtable\Web
 */
namespace Huxtable\Web;

$pathBaseWeb = __DIR__;
$pathSrcWeb	= $pathBaseWeb . '/src/Web';
$pathVendorWeb	= $pathBaseWeb . '/vendor';

/*
 * Initialize autoloading
 */
include_once( $pathSrcWeb . '/Autoloader.php' );
Autoloader::register();

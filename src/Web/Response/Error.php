<?php

/*
 * This file is part of Huxtable\Web
 */
namespace Huxtable\Web\Response;

class Error extends \Huxtable\Web\Response
{
	/**
	 * @return	void
	 */
	public function __construct()
	{
		print_r( $this );
	}
}

<?php

namespace Rex\Util;

class Sitepath
{	
	public function __construct()
	{
		// get site base (not absolute)
		$site = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
		
		// relative path
    if(!defined('SITEPATH_LOCAL')) define('SITEPATH_LOCAL', $site);
		// absolute path (eg for AJAXy stuff)
		$http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https' : 'http';
		$path = $http . '://' . $_SERVER['SERVER_NAME'] . SITEPATH_LOCAL;
		if(!defined('SITEPATH_ABSOLUTE')) define('SITEPATH_ABSOLUTE', $path);
	}
}
<?php

class Http
{
  const EXTERNAL = true;
  const ABSOLUTE = true;
  
  public function redirect($url = '/', $external = false)
  {
    $url = $external ? $url : $this->url($url);
    die(header('Location: ' . $url));
  }
  
  public function url($url = '', $absolute = false)
  {
		if (!defined('SITEPATH_LOCAL')){
			$site = new Sitepath();
		}
    $prefix = $absolute ? SITEPATH_ABSOLUTE : SITEPATH_LOCAL;
    return $prefix . '/' . ltrim($url, '/');
  }
  
  public function current()
  {
    return isset($_GET['q']) ? $_GET['q'] : '/';
  }
}
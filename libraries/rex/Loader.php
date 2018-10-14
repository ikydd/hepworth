<?php

namespace Rex;

class Loader
{
  public static function load($class)
  {
    $namespaces = explode("\\", $class);
    // first check to see if it's actually in the Rex namespace
    $rex = strtolower(array_shift($namespaces));
    if($rex != 'rex'){
      return false; // if it's not then we can safely ignore
    } else {
      // if it is, extract the end class name
      $cls = array_pop($namespaces);
      // lowercase it all
      $namespaces = array_map('strtolower', $namespaces);
      // and build the path
      $path = __DIR__ . DIRECTORY_SEPARATOR 
        . implode(DIRECTORY_SEPARATOR, $namespaces) . DIRECTORY_SEPARATOR . $cls . '.php';
      // if that file exists, then load it
      if(file_exists($path)){
        require_once($path);
      }
    }
  }
  
  public static function register()
  {
    spl_autoload_register('Rex\Loader::load');
  }
  
  public static function unregister()
  {
    spl_autoload_unregister('Rex\Loader::load');
  }
}

Loader::register();
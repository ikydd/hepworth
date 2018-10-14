<?php

/*
 *  This will allow you to inject variables into a template and then have the results
 *  returned to you
 */

class View
{
  protected $tools;
  
  public function __construct()
  {
    $this->load('Http');
    $this->load('Includes');
    $this->load('Feedback');
  }
  
  protected function load($class, $alias = null)
  {
    if(is_null($alias)) $alias = strtolower($class);
    $this->{$alias} = new $class;
  }
  
  // this will open a template file and return you the contents of it
  public function show($template, $variables = array())
  {
    // all our templates should be in the 'views' folder and end in .php
    $file = 'views/' . $template . '.php';
    
    // if the template file doesn't exist then just return nothing
    if(!file_exists($file) || !is_readable($file)) return '';
    
    // this will change the array to the following:
    // array('name' => 'some name') will become $name = 'some name'; 
    extract($variables); 
 
    // start the output buffer (ob) this will catch any 'echos' and 
    // stuff like that and store it in the buffer, stopping it being output to the browser. 
    ob_start();
    // here we read the file directly, including the content right here
    include $file;
    // this bit empties and stops the buffer, returning the contents to us as a string
    return ob_get_clean();
  }
}
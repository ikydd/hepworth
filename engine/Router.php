<?php

/*
 *  This class will take the url segments and then figure out which controller
 *  to load. The controller can then be loaded and run with 'execute'
 */

class Router
{
  private $url;
  private $segments;
  private $controller;
  private $page;
  private $arguments;
  private $defaultController = 'Gallery';
  private $defaultPage = 'index';
  
  // pass in the url request at the beginning
  public function __construct($url)
  {
    $this->url = $url;
    $this->parseUrl();
  }
  
  public function getController()
  {
    return $this->controller;
  }
  
  public function getPage()
  {
    return $this->page;
  }
  
  public function getArguments()
  {
    return $this->arguments;
  }
  
  public function getSegments()
  {
    return $this->segments;
  }
  
  public function getCanonical()
  {
	return '/' . ltrim(parse_url($this->url, PHP_URL_PATH), '/');
  }
  
  // this will populate the appropriate properties with controller, method and parts
  private function parseUrl()
  {
    // separate it out by the '/' sign
    $this->segments = explode('/', $this->url);
    // get rid of any empty bits just in case
    $this->segments = array_filter($this->segments);
    // make another copy so we can leave the original in tact in case we need it all later
    $this->arguments = $this->segments;
    
    // pop off the first item, this will be our controller
    $this->controller = array_shift($this->arguments);
    // controller are all lower case with a capital start so..
    $this->controller = ucfirst(strtolower($this->controller));
    // lastly we check it's a real class that is a sub-class of controller
    try{
      $ref = new ReflectionClass($this->controller);
      if(!$ref->isSubclassOf('Controller')) throw new Exception('Invalid controller');
      
      if($this->controller == 'Gallery') array_unshift($this->arguments, 'show');
      
    } catch(Exception $e){
      $this->controller = $this->defaultController;
    }
    
    // same method as above for this bit, though this is actually the second
    // bit of the segments seeing as we popped off the first bit just above
    // this will be the 'method' we need to call on our controller, which should
    // default to index if in doubt (oh yeah, and it doesn't need capitalisation this time)
    $this->page = strtolower(array_shift($this->arguments));
    try{
      $ref = new ReflectionClass($this->controller);
      if(!$ref->hasMethod($this->page)) throw new Exception('Method does not exist');
    } catch(Exception $e){
      $this->page = $this->defaultPage;
    }
  }
}
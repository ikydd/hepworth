<?php

/*
 *  All the page controllers should extend this class. There could be a ton of useful functions on here
 *  but that be part of a more involved thing. You might pre-load a View object for example, or include
 *  methods of adding CSS files and JS files and stuff like that. Maybe a method for using a different
 *  page template than the default.
 */

abstract class Controller
{
  protected $layout = 'layouts/default';
  protected $pageId = '';
  
  public function __construct()
  {
    $this->load('Feedback');
    $this->forms = new FormHandler($this);
    $this->forms->handle();
  }
  
  protected function load($class, $alias = null)
  {
    if(is_null($alias)) $alias = strtolower($class);
    $this->{$alias} = new $class;
  }
  
  public function getLayout()
  {
    return $this->layout;
  }
  
  public function getPageID()
  {
    return $this->pageId;
  }
  
  protected function setTitle($title, $html = false)
  {
    $this->title = $html ? $title : htmlentities($title);
  }
  
  public function getTitle()
  {
    return $this->title;
  }
  
  // in case your controller doesn't have an index file then it will use this as this method will be 
  // inherited into it
  public function index()
  {
    $defaultMessage = 'This is the default controller index, so your controller must have no
      index method defined - please fix this immediately!';
      
    $view = new View();
    return $view->show('standard/notice', array('message' => $defaultMessage));
  }
}
<?php

abstract class FormElement
{
  public $id;
  public $name;
  public $class;
  public $type;
  public $title;
  public $inline;
  public $leaf;
  
  public function __construct($name = null)
  {
    $this->name = $name;
  }
  
  public function render()
  {
    $vars = get_object_vars($this);
    
    $file = 'form/' . ($this->inline ? 'inline/' : '') . $this->type;
    
    $view = new View();
    return $view->show($file, $vars);
  }
}
<?php

abstract class TableElement
{
  protected $attributes = array();
  
  public function addAttribute($attr, $value)
  {
    $this->attributes[$attr] = $value;
  }
  
  public function addAttributes(array $attrs)
  {
    foreach($attrs as $attr => $value){
      $this->attributes[$attr] = $value;
    }
  }
  
  public function getAttributes()
  {
    return $this->attributes;
  }
  
  abstract function render();
}
<?php

class TableCell extends TableElement
{
  public $value = '';
  
  public function __construct($value = null, array $attributes = null)
  {
    if(!is_null($value)){
      $this->value = $value;
    }
    if(!is_null($attributes)){
      $this->addAttributes($attributes);
    }
  }
  
  public function render()
  {   
    $view = new View();
    
    return $view->show('standard/tables/cell', array('value' => $this->value, 'attributes' => $this->attributes));
  }
}
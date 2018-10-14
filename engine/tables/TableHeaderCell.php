<?php

class TableHeaderCell extends TableCell
{  
  public function render()
  {   
    $view = new View();
    
    return $view->show('standard/tables/header-cell', array('value' => $this->value, 'attributes' => $this->attributes));
  }
}
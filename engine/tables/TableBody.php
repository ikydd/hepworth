<?php

class TableBody extends TableElement
{
  protected $rows = array();
  
  public function __construct(array $rows = null)
  {
    if(!is_null($rows)){
      foreach($rows as $row){
        $this->addRow($row);
      }
    }
  }
  
  public function addRow($row)
  {
    if(!($row instanceof TableRow)){
      $row = new TableRow($row);
    }
    $this->rows[] = $row;
  }
  
  public function render()
  {
    $content = '';
    foreach($this->rows as $row){
      $content .= $row->render();
    }
    
    $view = new View();
    
    return $view->show('standard/tables/body', array('content' => $content, 'attributes' => $this->attributes));
  }
}
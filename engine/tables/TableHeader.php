<?php

class TableHeader extends TableBody
{  
  public function __construct(array $row = null)
  {
    if(!is_null($row)){
      foreach($row as $cell){
        $this->addCell($cell);
      }
    }
  }
  
  public function addCell($cell)
  {
    if(empty($this->rows)){
      $this->rows[] = new TableRow();
    }
    if(!($cell instanceof TableHeaderCell)){
      $cell = new TableHeaderCell($cell);
    }
    $this->rows[0]->addCell($cell);
  }
  
  public function render()
  {
    $content = '';
    foreach($this->rows as $row){
      $content .= $row->render();
    }
    
    $view = new View();
    
    return $view->show('standard/tables/header', array('content' => $content, 'attributes' => $this->attributes));
  }
}
<?php

class TableFooter extends TableBody
{  
  public function __construct(array $row = null)
  {
    if(!is_null($rows)){
      $this->addRow($row);
    }
  }
  
  public function addCell($cell)
  {
    if(empty($this->rows)){
      $this->rows[] = new TableRow();
    }
    if(!($cell instanceof TableCell)){
      $cell = new TableCell($cell);
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
    
    return $view->show('standard/tables/footer', array('content' => $content, 'attributes' => $this->attributes));
  }
}
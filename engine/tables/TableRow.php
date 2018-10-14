<?php

class TableRow extends TableElement
{
  protected $cells = array();
  
  public function __construct(array $cells = null)
  {
    if(!is_null($cells)){
      foreach($cells as $cell){
        $this->addCell($cell);
      }
    }
  }
  
  public function addCell($cell)
  {
    if(!($cell instanceof TableCell)){
      $cell = new TableCell($cell);
    }
    $this->cells[] = $cell;
  }
  
  public function render()
  {
    $content = '';
    foreach($this->cells as $cell){
      $content .= $cell->render();
    }
    
    $view = new View();
    
    return $view->show('standard/tables/row', array('content' => $content, 'attributes' => $this->attributes));
  }
}
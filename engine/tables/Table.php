<?php

class Table extends TableElement
{
  protected $header;
  protected $bodies = array();
  protected $footer;
  protected $caption;
  protected $attributes = array('class' => 'table table-striped');
  
  public function __construct($body = null)
  {
    if(!is_null($body)){
      if(!($body instanceof TableBody)){
        $body = new TableBody($body);
      }
      $this->bodies[] = $body;
    }
  }
  
  public function addHeader($header)
  {    
    if(!($header instanceof TableHeader)){
      $header = new TableHeader($header);
    }
    $this->header = $header;
  }
  
  public function addBody($body)
  {
    if(!($body instanceof TableBody)){
      $body = new TableRow($body);
    }
    $this->bodies[] = $body;
  }
  
  public function addFooter($footer)
  {    
    if(!($footer instanceof TableFooter)){
      $footer = new TableFooter($footer);
    }
    $this->footer = $footer;
  }
  
  public function addRow($row)
  {
    if(empty($this->bodies)){
      $this->bodies[] = new TableBody();
    }
    if(!($row instanceof TableRow)){
      $row = new TableRow($row);
    }
    $this->bodies[0]->addRow($row);
  }
  
  public function addCaption($caption)
  {
    $this->caption = $caption;
  }
  
  public function render()
  {
    $content = '';
    if($this->header){
      $content .= $this->header->render();
    }
    foreach($this->bodies as $body){
      $content .= $body->render();
    }
    if($this->footer){
      $content .= $this->footer->render();
    }
    
    $view = new View();
    
    return $view->show('standard/tables/table', array('content' => $content, 'caption' => $this->caption, 'attributes' => $this->attributes));
  }
}
<?php

class FormSectionTabledrag extends FormSection
{
  public $rowCount = 0;
  public $emptyText = 'No elements to show';
  public $header = array();
  public $tree = true;
  protected $rows = array();
  
  public function fullHeader()
  {
    return array_merge($this->header, array(''));
  }
  
  public function addElement($element)
  {
    $element->weight = $this->rowCount++;
    $element->input->defaultValue = $element->weight;
    $this->rows[] = $element;
    parent::addElement($element->input);
  }
  
  public function render()
  {
    $http = new Http;
    $inc = new Includes;
    $path = new Path;
    $view = new View;
    
    $inc->js($http->url($path->js('jquery.tablednd.js')));
    $inc->js($http->url($path->js('tabledrag.js')));
    $inc->css($http->url($path->css('tabledrag.css')));
  
    $table = new Table;
    $table->addCaption('Changes have been made - save to confirm');
    $atts = $table->getAttributes();
    $table->addAttribute('class', $atts['class'] .= ' tabledrag-table');
    
    $weightHead = new TableHeaderCell('Weight');
    $weightHead->addAttribute('class', 'tabledrag-weight');
    
    $header = array_merge(array('&nbsp;'), $this->header, array($weightHead));
    $table->addHeader($header);
    
    foreach($this->rows as $row){
      
      $handle = new TableCell('<span class="tabledrag-handle"><img src="' . $http->url($path->img('draggable.png')) . '" /></span>');
      $handle->addAttribute('class', 'tabledrag-handle-cell');
      
      $r = array($handle);
      foreach($row->fields as $field){
        $r[] = $field;
      }
      
      $weight = new TableCell($row->input->render());
      $weight->addAttribute('class', 'tabledrag-weight');
      $r[] = $weight;
      
      $table->addRow($r);
    }
    
    return $view->show('standard/tabledrag-box', array('table' => $table->render()));
  }
}
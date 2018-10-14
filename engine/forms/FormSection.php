<?php

class FormSection extends FormElement
{
  public $content;
  public $type = 'section';
  public $tree = false;
  public $elements = array();
  
  
  public function addElement($element)
  {
    if($this->tree){
      $this->branch($element);
    }
    $this->elements[] = $element;
  }
  
  public function addElements($elements)
  {
    foreach($elements as $el){
      $this->addElement($el);
    }
  }
  
  protected function branch($element)
  {
    if($element instanceof FormSection && $this->name){
      $element->tree();
    }
    $element->name = "{$this->name}[{$element->name}]";
    $element->leaf = true;
  }
  
  public function tree()
  {
    $this->tree = true;
    foreach($this->elements as $element){
      $this->branch($element);
    }
  }
  
  public function render()
  {
    $els = array();
    foreach($this->elements as $element){
      $els[] = $element->render();
    }
    $this->content = implode(' ', $els);
    return parent::render();
  }
}
<?php

class FormError extends Exception{}

class FormHandler
{
  private $form;
  private $errors;
  
  public function handle()
  {
    Memo::delete('form-errors');
    
    if($this->isSubmitted()){
      
      $form = $this->getForm();
      
      if($form && $form->validateForm($this->getFormValues())){
        
        $form->submitForm($this->getFormValues());
        
        $forms = Memo::fetch('forms');
        unset($forms[$form->getId()]);
        Memo::store('forms', $forms);
        
        $form->redirect();
      }
    }
  }
  
  private function isSubmitted()
  {
    return $_POST && isset($_POST['form-submitted']) && $_POST['form-submitted'] == 1;
  }
  
  private function getForm()
  {
    if($this->form) return $this->form;
    
    if($_POST && isset($_POST['form-id']) && $_POST['form-id']){
      $id = $_POST['form-id'];
      
      $forms = Memo::fetch('forms');
      
      if(isset($forms[$id])){
        $this->form = $forms[$id];
        return $this->form;
      } else {
        throw new FormError('Invalid form');
      }
    }
  }
  
  private function getFormValues()
  {
    $values = array();
    $elements = $this->getForm()->elements();
    $iter = new SubmittableElementFilter(new RecursiveIteratorIterator(new RecursiveArrayIterator($elements)));
    foreach($iter as $element){
      if(strpos($element, '[') !== false){
        $post = $_POST;
        $val = &$values;
        while($bracket = strpos($element, '[')){
          $segment = rtrim(substr($element, 0, $bracket), ']');
          $element = ltrim(substr($element, $bracket), '[');
          $post = $post[$segment];
          $val = &$val[$segment];
        }
        if(isset($post)){
          $val = $post;
        } else {
          $val = false;
        }
      } else {
        if(isset($_POST[$element])){
          $values[$element] = $_POST[$element];
        } else {
          $values[$element] = false;
        }
      }
    }
    
    return $values;
  }
}
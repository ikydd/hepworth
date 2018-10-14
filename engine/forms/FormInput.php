<?php

abstract class FormInput extends FormElement
{
  public $error;
  public $placeholder;
  public $description;
  public $defaultValue;
  public $value;
  public $required;
  
  protected function getValue()
  {
    return isset($_POST[$this->name]) ? $_POST[$this->name] : ($this->value ? $this->value : $this->defaultValue);
  }
  
  protected function getErrors()
  {
    $errors = Memo::fetch('form-errors');
    return $errors ? $errors : array();
  }
  
  protected function getError()
  {
    $errors = $this->getErrors();
    return (bool) isset($errors[$this->name]);
  }
  
  public function render()
  {
    $this->value = $this->getValue();
    $this->error = $this->getError();
    
    return parent::render();
  }
}
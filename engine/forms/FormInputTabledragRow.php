<?php

class FormInputTabledragRow extends FormInput
{
  public $weight;
  public $input;
  public $name;
  public $fields = array();
  
  public function __construct($name)
  {
    $this->name = $name;
    $this->input = new FormInputHidden($name);
  }
}
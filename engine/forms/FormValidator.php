<?php

class FormValidationFailure extends Exception {}

class FormValidator
{
  public function required($element, $value)
  {
    $title = $element->title ? $element->title : 'Field';
    if(is_null($value) || !$value) throw new FormValidationFailure($title . ' is required');
  }
}
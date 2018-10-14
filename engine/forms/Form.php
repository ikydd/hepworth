<?php

abstract class Form
{
  public $method = 'POST';
  public $action;
  public $enctype = 'application/x-www-form-urlencoded';
  public $redirect;
  protected $elements = array();
  protected $errors = array();
  protected $id;
  protected $inline;
  
  public function __construct()
  {
    $this->id = md5(get_class($this) . microtime(true));
    Memo::stack('forms', $this, $this->id);
  }
  
  public function getId()
  {
    return $this->id;
  }

  private function storeErrors()
  {
    Memo::store('form-errors', $this->errors);
  }
  
  protected function getErrors()
  {
    $errors = Memo::remove('form-errors');
    
    return is_array($errors) ? $errors : array();
  }
  
  protected function clearErrors()
  {
    Memo::delete('form-errors');
  }
  
  private function checkForError($name)
  {
    $errors = $this->getErrors();
    
    if(isset($errors[$name])){
      return $errors[$name] ? $errors[$name] : 'Error';
    }
  }
  
  abstract public function elements();
  
  protected function formElements()
  {
    $elements = $this->elements();
    
    if(!is_array($elements)) $elements = array($elements);
    
    $id = new FormInputHidden('form-id');
    $id->value = $this->id;
    
    $submitted = new FormInputHidden('form-submitted');
    $submitted->value = 1;
    
    $elements[] = $id;
    $elements[] = $submitted;
    
    return $elements;
  }
  
  public function render()
  {
    $content = $this->renderElements();
    $this->clearErrors();
    
    $view = new View();
    
    $file = 'form/' . ($this->inline ? 'inline/' : '') . 'form';
    
    return $view->show($file, array(
      'id' => $this->id,
      'method' => $this->method, 
      'enctype' => $this->enctype, 
      'action' => $this->action, 
      'content' => $content
    ));
  }
  
  protected function renderElements()
  {
    $content = '';
    foreach($this->formElements() as $element){
      $content .= $element->render();
    }
    return $content;
  }

  protected function error($name, $err)
  {
    if(!isset($this->errors[$name])) $this->errors[$name] = array();
    $this->errors[$name][] = $err;
    
    $feedback = new Feedback();
    $feedback->error($err);
  }
  
  protected function message($msg)
  {
    $feedback = new Feedback();
    $feedback->message($msg);
  }
  
  public function validateForm($values)
  {
    $this->errors = array();
    $this->genericValidation($values);
    $this->validate($values);
    $this->storeErrors();
    return empty($this->errors);
  }
  
  protected function genericValidation($values)
  {
    foreach($this->elements() as $element){
      if($element instanceof FormSection){
        foreach($element->elements as $el){
          try{
            if(strpos($el->name, '[') !== false){
              $name = $el->name;
              $val = $values;
              while($bracket = strpos($name, '[')){
                $segment = rtrim(substr($name, 0, $bracket), ']');
                $name = ltrim(substr($name, $bracket), '[');
                $val = $val[$segment];
              }
              $this->genericValidate($el, $val);
            } else {
              $this->genericValidate($el, $values[$el->name]);
            }
          } catch(FormValidationFailure $e){
            $this->error($el->name, $e->getMessage());
          }
        }
      } else {
        try{            
          if(strpos($element->name, '[') !== false){
            $name = $element->name;
            $val = $values;
            while($bracket = strpos($name, '[')){
              $segment = rtrim(substr($name, 0, $bracket), ']');
              $name = ltrim(substr($name, $bracket), '[');
              $val = $val[$segment];
            }
            $this->genericValidate($element, $val);
          } else {
            $this->genericValidate($element, $values[$element->name]);
          }
        } catch(FormValidationFailure $e){
          $this->error($element->name, $e->getMessage());
        }
      }
    }
  }
  
  protected function genericValidate($element, $value)
  {
    $validator = new FormValidator();
    if($element->required){
      $validator->required($element, $value);
    }
  }
  
  protected function validate($values)
  {
    // $feedback = new Feedback();
    // $feedback->error('This form has no validation');
  }
  
  public function submitForm($values)
  {
    $this->submit($values);
  }
  
  protected function submit($values) {}
  
  public function redirect()
  {
    $http = new Http();
    $url = $this->redirect ? $this->redirect : $http->current();
    // $http->redirect($url);
    // this simple redirect is inadequate as it doesn't seem to fix
    // the problem of refreshing reposting the info anymore
    // so more drastic measures must be taken...
    $http->redirect('redirect?redirect=' . urlencode($url));
  }
}
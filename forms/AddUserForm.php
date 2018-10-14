<?php

class AddUserForm extends Form
{
  public function elements()
  {
    $name = new FormInputText('name');
    $name->title = 'Name';
    $name->required = true;
    
    $email = new FormInputText('email');
    $email->title = 'Email';
    $email->required = true;
    
    $password = new FormInputPassword('password');
    $password->title = 'Password';
    $password->description = 'Must be six characters or longer';
    $password->required = true;
    
    $confirm = new FormInputPassword('confirm');
    $confirm->title = 'Confirm Password';
    $confirm->required = true;
    
    $submit = new FormInputSubmit('submit');
    $submit->value = 'Save';
    
    $actions = new FormSectionActions();
    $actions->addElement($submit);
    
    return array($name, $email, $password, $confirm, $actions);
  }
  
  public function validate($values)
  {
    $model = new UserModel;
    if($values['name'] && $model->findByName($values['name'])){
      $this->error('name', 'Sorry, but that name is already in use');
    }
    
    if($values['password'] && strlen($values['password']) < 6){
      $this->error('password', 'Password is not long enough');
    }
    
    if($values['password'] != $values['confirm']){
      $this->error('password', 'Passwords do not match');
    }
    
    if($values['email'] && !filter_var($values['email'],  FILTER_VALIDATE_EMAIL)){
      $this->error('email', 'Invalid email address');
    }
  }
  
  public function submit($values)
  {
    $hash = new MyHasher;
    
    $user = new User();
    $user->name = $values['name'];
    $user->email = $values['email'];
    $user->pass = $hash->hash($values['password']);
    
    $user->accept(new NewUserVisitor);
    
    $this->message('New user created');
  }
}
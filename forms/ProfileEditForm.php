<?php

class ProfileEditForm extends Form
{
  public function __construct(User $user)
  {
    $this->user = $user;
    parent::__construct();
  }
  public function elements()
  {
    $pass = new FormInputPassword('current-pass');
    $pass->title = 'Current Password';
    $pass->required = true;
    
    $newPass = new FormInputPassword('new-pass');
    $newPass->title = 'New Password';
    
    $conPass = new FormInputPassword('con-pass');
    $conPass->title = 'Confirm New Password';
    
    $email = new FormInputText('email');
    $email->title = 'Email';
    $email->defaultValue = $this->user ? $this->user->email : '';
    $email->required = true;
    
    $submit = new FormInputSubmit('submit');
    $submit->value = 'Save';
    
    $actions = new FormSectionActions();
    $actions->addElement($submit);
    
    return array($pass, $newPass, $conPass, $email, $actions);
  }
  
  public function validate($values)
  {
    $auth = new Auth;
    try{
      $auth->authenticate('standard', $this->user, $values['current-pass']);
    } catch (AuthException $e){
      $this->error('current-pass', 'Incorrect password');
    }
    
    if($values['new-pass'] && $values['new-pass'] != $values['con-pass']){
      $this->error('con-pass', 'Password confirmation not the same');
    }
    
    if(!$values['email'] && !filter_var($values['email'],  FILTER_VALIDATE_EMAIL)){
      $this->error('email', 'Invalid email address');
    }
  }
  
  public function submit($values)
  {
    $hash = new MyHasher;
    $email = false;
    $pass = false;

    if($this->user->email != $values['email']){
      $email = true;
      $this->user->email != $values['email'];
    };
    
    if ($values['new-pass']) {
      $pass = true;
      $this->user->pass = $hash->hash($values['new-pass']);
    }
    
    $model = new UserModel();
    $model->save($this->user);

    if($email) $this->message('Email address changed');
    if($pass) $this->message('Password changed');
  }
}
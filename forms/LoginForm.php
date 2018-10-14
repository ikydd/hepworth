<?php

class LoginForm extends Form
{
  public function elements()
  {
    $user = new FormInputText('user');
    $user->title = 'Username';
    $user->required = true;
    
    $pass = new FormInputPassword('pass');
    $pass->title = 'Password';
    $pass->required = true;
    
    $remember = new FormInputCheckbox('remember');
    $remember->title = 'Remember me?';
    
    $login = new FormInputSubmit('submit');
    $login->value = 'Login';
    
    $actions = new FormSectionActions;
    $actions->addElement($login);
    
    return array($user, $pass, $remember, $actions);
  }
  
  public function validate($values)
  {
    if(!$values['user'] || !$values['pass']) return;
    
    try{
    
      $model = new UserModel();
      $users = $model->findWhere(array('name' => $values['user']));
      
      if(empty($users)) {
        throw new Exception('Username invalid or password did not match');
      }
      
      $user = $users[0];
      
      try{
        $auth = new Auth();
        $auth->authenticate('standard', $user, $values['pass']);
      } catch(AuthException $e){
        $user->accept(new LoginFailVisitor);
        throw new Exception($e->getMessage());
      }
      
      $user->accept(new LoginPassVisitor);
     
    } catch(Exception $e){
      $this->error('', $e->getMessage());
    }
  }
  
  public function submit($values)
  {
    $model = new UserModel();
    $users = $model->findWhere(array('name' => $values['user']));
      
    $user = $users[0];
      
    $user->accept(new LoginPassVisitor);
    
    $auth = new Auth();
    $auth->storeUser($user);
    
    if($values['remember']){
      $user->accept(new RememberMeVisitor);
    }
    
    $http = new Http();
    $http->redirect('collections');
  }
}
<?php

class Profile extends AdminController
{
  public function index()
  {
    return $this->edit();
  }
  
  public function add()
  {
    $form = new AddUserForm;
    
    $content = '';
    $content .= $form->render();
    
    return $this->render($content, 'topmenu');
  }
  
  public function edit()
  {    
    // $this->setTitle('Edit Profile');
    
    $content = '';
    if($user = Auth::isLoggedIn()){
  
      $form = new ProfileEditForm($user);

      $content .= $form->render();
    
    } else {
      $view = new View();
       $content .= $view->show('standard/notice', array('message' => 'You need to be logged in to edit your profile!
        In fact what on earth are you doing here at all?'));
    }
    
    return $this->render($content, 'topmenu');
  }
}
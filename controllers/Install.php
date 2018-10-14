<?php

class Install extends BaseController
{
  public function index()
  {
    $db = Database::connect();
    
    $test = "SHOW TABLES LIKE 'users'";
    
    $stm = $db->prepare($test);
    
    $stm->execute();
    
    if($stm->rowCount()){
    
      $stm = $db->prepare("SELECT * FROM users");
      
      $stm->execute();
      
      if($stm->rowCount()){
    
      $this->feedback->error('Invalid request');
      $this->http->redirect();
      
      } else {
        $this->populate();
      }
    }
    
    $sql = file_get_contents(dirname(__FILE__) . "/../configs/install.sql");
    
    $stm = $db->prepare($sql);
    
    $res = $stm->execute();
    
    $this->http->redirect('install');
  }
  
  private function populate()
  {
    $model = new SettingModel();
    $model->set('contact-address', 'iain.kydd@gmail.com');
    $model->set('about-title', '');
    
    $hash = new MyHasher;

    $user = new User();
    $user->name = 'admin';
    $user->pass = $hash->hash('password');
    $user->email = 'iain.kydd@gmail.com';
    
    $user->accept(new NewUserVisitor);
    
    $this->feedback->message('Site installed');
    $this->http->redirect();
  }
}
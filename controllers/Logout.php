<?php

class Logout extends BaseController
{
  public function index()
  {
    Auth::clearUser();
    // delete cookies  time()-60*60*24*30
    setcookie('ident', 'DELETED', time()+30, '/');
    setcookie('token', 'DELETED', time()+30, '/');
    
    $this->feedback->message('You have been logged out');
    $this->http->redirect();
  }
}
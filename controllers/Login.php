<?php

class Login extends BaseController
{
  public function index()
  {
    $form = new LoginForm();
    
    $content = '';
    $content .= $form->render();

    return $this->render($content, 'plain');
  }
}
<?php 

class Forbidden extends BaseController
{
  public function index()
  {
    return $this->render('I think should you move on...', 'plain');
  }
}
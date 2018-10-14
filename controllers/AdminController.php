<?php

abstract class AdminController extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    if(!Auth::isLoggedIn()) $this->http->redirect('forbidden');
  }
}
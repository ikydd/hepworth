<?php

class Redirect extends Controller
{
  public function index()
  {
    $url = isset($_GET['redirect']) ? $_GET['redirect'] : '';
    $http = new Http;
    $http->redirect($url);
  }
}
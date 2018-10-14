<?php

class Feedback extends Memo
{
  public function getErrors()
  {
    return self::remove('errors');
  }
  
  public function getMessages()
  {
    return self::remove('messages');
  }
  
  public function message($msg)
  {
    self::stack('messages', $msg);
  }
  
  public function error($err)
  {
    self::stack('errors', $err);
  }
}
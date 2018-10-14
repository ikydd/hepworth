<?php

class Path
{
  public function js($url)
  {
    return 'public/js/' . $url;
  }
  
  public function css($url)
  {
    return 'public/css/' . $url;
  }
  
  public function img($url)
  {
    return 'public/img/' . $url;
  }
}
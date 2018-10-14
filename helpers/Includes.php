<?php

class Includes
{
  private static $css = array();
  private static $js = array();
  private static $jsSettings = array();
  
  public function css($url)
  {
    self::$css[] = $url;
  }
  
  public function js($data, $type = 'file')
  {
    switch($type){
      case 'setting':
        self::$jsSettings += $data;
        break;
      case 'file':
      default:
        self::$js[] = $data;
        break;
    }
  }
  
  public function getCSS()
  {
    return self::$css;
  }
  
  public function getJS()
  {
    return self::$js;
  }
  
  public function getJSsettings()
  {
    return self::$jsSettings;
  }
}
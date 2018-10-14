<?php

class Database
{
  private static $dbs = array();
  public static function connect($db = 'default')
  {
    $details = self::credentials($db);
    if(!isset(self::$dbs[$db]) || !(self::$dbs[$db] instanceof PDO)){
      return new PDO("mysql:dbname={$details['database']};host={$details['host']}", 
        $details['username'], 
        $details['password'], 
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    return self::$dbs[$db];
  }
  
  private static function credentials($db)
  {
    $file = dirname(__FILE__) . "/../configs/{$db}.php";
    require $file;
    
    return $config;
  }
}
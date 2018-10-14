<?php

/*
 *  This kind of file is only needed in Object-oriented style - it will look in specified folders
 *  and automatically open/read the php file for that class if it exists. this function is called
 *  dynamically by the PHP engine each time a class in called that it doesn't recognise and it basically
 *  saves us writing a bunch of includes and requires all over the place
 */
class Autoloader 
{
  // list searchable folders it might be in here (we don't want to search all of them
  // for performance reasons, best to limit it to a few
  private static $folders = array('controllers', 'models', 'engine', 'helpers', 'forms');
  
  // this is what will be called by PHP to find the class
  public static function load($class){
  
    $file = DIRECTORY_SEPARATOR . $class . '.php';
    
    // iterate over each folder looking for the class until class file is found
    foreach(self::$folders as $folder){
    
      $path = $folder . $file;
    
      if(file_exists($path) && is_readable($path)){
        include $path;
        return;
      }
    
      $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder),
                                              RecursiveIteratorIterator::CHILD_FIRST);
        
      foreach($iterator as $dir){
        if($dir->isDir()){
          $path = $dir->getPathname() . $file;
          if(file_exists($path) && is_readable($path)){
            include $path;
            return;
          }
        }
      }
    }
  }
}

// we do actually have to register the class explicitly though
spl_autoload_register('Autoloader::load');
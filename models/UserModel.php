<?php

class UserModel extends Model
{
  protected $tableName = 'users';
  protected $entity = 'User';
  
  public function create($obj)
  {
    $obj->created = date('Y-m-d H:i:s');
    
    parent::create($obj);
  }
  
  public function findByName($name)
  {
    $res = $this->findWhere(array('name' => $name));
    
    return isset($res[0]) ? $res[0] : false;
  }
}
<?php

class SettingModel extends Model
{
  protected $tableName = 'settings';
  protected $entity = 'Setting';
  protected $primaryKey = 'key';
  
  public function get($id)
  {
    $setting = $this->find($id);
    
    return $setting ? $setting->value : false;
  }
  
  public function set($id, $value)
  {
    $s = new stdClass;
    $s->key = $id;
    $s->value = $value;
    
    $this->save($s);
  }
  
  public function save($obj)
  {
    if(!$obj->key) throw new Exception('Invalid setting - must have key');
    
    $set = $this->find($obj->key);
    if($set){
      return $this->update($obj);
    } else {
      return $this->create($obj);
    }
  }
}
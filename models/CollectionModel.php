<?php

class CollectionModel extends Model
{
  protected $tableName = 'collections';
  protected $entity = 'Collection';
  
  public function create($obj)
  {
    $obj->created = date('Y-m-d H:i:s');
    
    parent::create($obj);
  }
  
  public function findBySlug($slug)
  {
    $collections = $this->findWhere(array('slug' => $slug));
    $col = isset($collections[0]) ? $collections[0] :  false;
    
    return $col;
  }
}
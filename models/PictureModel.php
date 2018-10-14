<?php

class PictureModel extends Model
{
  protected $tableName = 'pictures';
  protected $entity = 'Picture';
 
  public function create($obj)
  {
    $obj->created = date('Y-m-d H:i:s');
    
    parent::create($obj);
  }
  
  public function delete($id)
  {
    $pic = $this->find($id);
    $result = parent::delete($id);
    
    // delete the images
    if($result){
      unlink($pic->mini);
      unlink($pic->thumb);
      unlink($pic->medium);
      unlink($pic->full);
    }
    
    return $result;
  }
}
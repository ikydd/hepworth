<?php

class EditAboutPageForm extends EditPageForm
{
  public $enctype = 'multipart/form-data';
  
  public function elements()
  {
    $elements = parent::elements();
    
    $file = new FormInputFile('image');
    $file->title = 'Image';
    $file->description = 'This will get shrunk to 380px wide';
    
    $remove = new FormInputCheckbox('remove');
    $remove->title = 'Remove image?';
    $remove->description = 'If you remove the image the page will revert back to the old view';    
    
    array_splice($elements, 3, 0, array($file, $remove));
    
    return $elements;
  }
  
  public function submit($values)
  {
    if($values['remove']){
    
      $model = new SettingModel;
      $model->set('about-image', '');
      
    } else {
      if($_FILES['image']['tmp_name']){
          
        // save mini
        $img = new Image($_FILES['image']['tmp_name']);
        $img->scale(380, 720)->save('public/uploads/misc/' . $_FILES['image']['name']);
        
        $model = new SettingModel;
        $model->set('about-image', 'public/uploads/misc/' . $_FILES['image']['name']);
      }
    }
    
    parent::submit($values);
  }
}
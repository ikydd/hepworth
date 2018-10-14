<?php

class EditPrintsPageForm extends EditPageForm
{  
  public function elements()
  {
    $elements = parent::elements();
    
    $model = new SettingModel;
    
    $show = new FormInputCheckbox('show');
    $show->title = 'Show Prints Page?';
    $show->description = 'Toggle whether you actually want this on or not';  
    $show->defaultValue = $model->get('show-prints');
    
    array_splice($elements, 3, 0, array($show));
    
    return $elements;
  }
  
  public function submit($values)
  {
    $model = new SettingModel;
    $model->set('show-prints', $values['show']);
    
    parent::submit($values);
  }
}
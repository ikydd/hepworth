<?php

class EditShopPageForm extends EditPageForm
{  
  public function elements()
  { 
    $model = new SettingModel;
    
    $url = new FormInputText('url');
    $url->title = 'Shop url';
    $url->description = 'The url of the shop';  
    $url->defaultValue = $model->get('shop-url');
   
    
    $show = new FormInputCheckbox('show');
    $show->title = 'Show Shop Page?';
    $show->description = 'Toggle whether you actually want this on or not';  
    $show->defaultValue = $model->get('show-shop');
    
    $submit = new FormInputSubmit('submit');
    $submit->value = 'Save';
    
    $actions = new FormSectionActions;
    $actions->addElement($submit);
    
    return array($url, $show, $actions);
  }
  
  public function submit($values)
  {
    $model = new SettingModel;
    $model->set('show-shop', $values['show']);
    $model->set('shop-url', $values['url']);
    
    $this->message('Content saved');
  }
}
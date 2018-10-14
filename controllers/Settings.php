<?php

class Settings extends AdminController
{
  public function index()
  {
    $model = new SettingModel;
    $settings = $model->findWhere();
    
    $values = array();
    foreach($settings as $setting){
      $values[$setting->key] = $setting->value;
    }
    
    $settingsForm = new SettingsForm($values);
    
    $content = '';
    $content .= $settingsForm->render();
    
    return $this->render($content, 'topmenu');
  }
}
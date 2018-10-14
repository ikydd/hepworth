<?php

class EditAboutForm extends Form
{
  public function __construct($title, $content, $file)
  {
    $this->title = $title;
    $this->content = $content;
    $this->file = $file;
    
    parent::__construct();
  }
  public function elements()
  {
    $title = new FormInputText('title');
    $title->title = 'About Page Title';
    $title->defaultValue = $this->title;
    $title->description = 'This is optional and if you leave this blank
      it will just cut the title off the top';
    
    $content = new FormInputTextarea('content');
    $content->title = 'About Page Content';
    $content->defaultValue = $this->content;
    $content->rows = 15;
    $content->required = true;
    
    $submit = new FormInputSubmit('submit');
    $submit->value = 'Save';
    
    $actions = new FormSectionActions;
    $actions->addElement($submit);
    
    return array($title, $content, $actions);
  }
  
  public function validate($values)
  {
    // nothing
  }
  
  public function submit($values)
  {
    file_put_contents($this->file, $values['content']);
    
    $model = new SettingModel();
    $model->save((object) array('key' => 'about-title', 'value' => $values['title']));
    
    $this->message('About content saved');
  }
}
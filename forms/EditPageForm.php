<?php

class EditPageForm extends Form
{
  public function __construct($page)
  {
    $this->page = $page;
    
    parent::__construct();
  }
  public function elements()
  {
    $id = new FormInputHidden('id');
    $id->value = $this->page->id;
    
    $title = new FormInputText('title');
    $title->title = 'Page Title';
    $title->defaultValue = $this->page->title;
    $title->description = 'This is optional and if you leave this blank
      it will just cut the title off the top';
    
    $content = new FormInputTextarea('content');
    $content->title = 'Page Content';
    $content->description = htmlentities('Additional formatting: <b>bold</b> and <i>italic</i>');
    $content->defaultValue = $this->page->content;
    $content->rows = 15;
    
    $submit = new FormInputSubmit('submit');
    $submit->value = 'Save';
    
    $actions = new FormSectionActions;
    $actions->addElement($submit);
    
    return array($id, $title, $content, $actions);
  }
  
  public function validate($values)
  {
    // nothing
  }
  
  public function submit($values)
  {
    $page = new stdClass;
    $page->id = $values['id'];
    $page->title = $values['title'];
    $page->content = $values['content'];
    
    $model = new PageModel;
    $model->save($page);
    
    $this->message('Content saved');
  }
}
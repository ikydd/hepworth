<?php

class ReorderCollectionForm extends Form
{

  public function __construct($pictures)
  {
    $this->pictures = $pictures;
    
    parent::__construct();
  }
  
  public function elements()
  {
    $table = new FormSectionTabledrag('pictures');
    $table->header = array('&nbsp', 'Title', 'Caption', '&nbsp;');
    
    $http = new Http;
    $view = new View;
    
    foreach($this->pictures as $picture){
      $e = new FormInputTabledragRow($picture->id);
      $actions = array(
        $view->show('portfolio/icon-action-link', array('icon' => 'pencil', 'url' => $http->url('pictures/edit/' . $picture->id))),
        $view->show('portfolio/icon-action-link', array('icon' => 'trash', 'url' => $http->url('pictures/delete/' . $picture->id)))
      );
      $e->fields = array(
        '<img src="' . $http->url($picture->mini) . '" />',
        $picture->title,
        $picture->caption,
        implode(' &nbsp; ', $actions)
      );
      $table->addElement($e);
    }
    
    $submit = new FormInputSubmit('submit');
    $submit->value = 'Save';
    
    $actions = new FormSectionActions;
    $actions->addElement($submit);
    
    return array($table, $actions);
  }
  
  public function validate($values)
  {
  }
  
  public function submit($values)
  {
    $model = new PictureModel;
    foreach($values['pictures'] as $id => $weight){
      $col = new stdClass;
      $col->id = $id;
      $col->position = $weight;
      
      $model->save($col);
    }
    $this->message('New order saved');
  }
}
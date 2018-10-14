<?php

class ReorderCollectionsForm extends Form
{

  public function __construct($collections)
  {
    $this->collections = $collections;
    
    parent::__construct();
  }
  
  public function elements()
  {
    $table = new FormSectionTabledrag('collections');
    $table->header = array('Name', 'Slug', 'Description', '&nbsp;');
    
    $http = new Http;
    $view = new View;
    
    foreach($this->collections as $collection){
      $e = new FormInputTabledragRow($collection->id);
      $actions = array(
        $view->show('portfolio/icon-action-link', array('icon' => 'eye-open', 'url' => $http->url('portfolio/' . $collection->slug))),
        $view->show('portfolio/icon-action-link', array('icon' => 'pencil', 'url' => $http->url('collections/edit/' . $collection->id))),
        $view->show('portfolio/icon-action-link', array('icon' => 'trash', 'url' => $http->url('collections/delete/' . $collection->id)))
      );
      $e->fields = array(
        $collection->title, 
        $collection->slug,
        $collection->description,
        implode( ' &nbsp; ', $actions)
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
    $model = new CollectionModel;
    foreach($values['collections'] as $id => $weight){
      $col = new stdClass;
      $col->id = $id;
      $col->position = $weight;
      
      $model->save($col);
    }
    $this->message('New order saved');
  }
}
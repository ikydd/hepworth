<?php

class DeleteCollectionForm extends Form
{
  public function __construct(Collection $collection)
  {
    $this->collection = $collection;
    
    parent::__construct();
  }
  public function elements()
  {
    $id = new FormInputHidden('id');
    $id->value = $this->collection->id;
    
    $mark = new FormInputMarkup('title');
    $mark->markup = 'Are you sure you want to delete ' . $this->collection->title . '?';
    
    $submit = new FormInputSubmit('delete');
    $submit->value = 'Delete';
    
    $http = new Http;
    
    $cancel = new FormInputCancel('cancel');
    $cancel->value = 'Cancel';
    $cancel->url = $http->url('collections');
    
    $actions = new FormSectionActions();
    $actions->addElements(array($submit, $cancel));
    
    return array($id, $mark, $actions);
  }
  
  public function validate($values)
  {
    if(!$values['id'] || !is_numeric($values['id'])){
      $this->error('id', 'There has been a problem with this form');
    }
  }
  
  public function submit($values)
  {
    $model = new CollectionModel;
    $model->delete($values['id']);

    $this->message('Collection deleted');
    
    $http = new Http;
    $http->redirect('collections');
  }
}
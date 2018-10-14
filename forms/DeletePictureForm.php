<?php

class DeletePictureForm extends Form
{
  public function __construct(Picture $picture)
  {
    $this->picture = $picture;
    
    parent::__construct();
  }
  public function elements()
  {
    $id = new FormInputHidden('id');
    $id->value = $this->picture->id;
    
    $mark = new FormInputMarkup('title');
    $mark->markup = 'Are you sure you want to delete ' . $this->picture->title . '?';
    
    $submit = new FormInputSubmit('delete');
    $submit->value = 'Delete';
    
    $http = new Http;
    
    $cancel = new FormInputCancel('cancel');
    $cancel->value = 'Cancel';
    $cancel->url = $http->url('pictures');
    
    $actions = new FormSectionActions();
    $actions->addElements(array($submit, $cancel));
    
    return array($id, $mark, $actions);
  }
  
  public function submit($values)
  {
    $model = new PictureModel;
    $model->delete($values['id']);

    $this->message('Picture deleted');
    
    $http = new Http;
    $http->redirect('pictures');
  }
}
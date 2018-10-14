<?php

class AddEditCollectionForm extends Form
{
  public function __construct(Collection $collection = null)
  {
    if($collection){
      $this->collection = $collection;
    } else {
      $this->collection = new Collection();
    }
    
    parent::__construct();
  }
  
  public function elements()
  {
    $id = new FormInputHidden('id');
    $id->value = $this->collection->id;
    
    $title = new FormInputText('title');
    $title->title = 'Title';
    $title->description = 'The name of your collection';
    $title->defaultValue = $this->collection->title;
    $title->required = true;
    
    $slug = new FormInputText('slug');
    $slug->title = 'URL Slug';
    $slug->description = 'This is the short url segment that will identify this collection. This should only be letters and hypens.';
    $slug->defaultValue = $this->collection->slug;
    $slug->required = true;
    
    $desc = new FormInputTextarea('description');
    $desc->title = 'Description';
    $desc->description = 'A little bit of blurb about the collection as a whole that will appear at the top
      of the page';
    $desc->defaultValue = $this->collection->description;
    
    $submit = new FormInputSubmit('submit');
    $submit->value = $this->collection->id ? 'Edit' : 'Add';
    
    $actions = new FormSectionActions;
    $actions->addElement($submit);
    
    return array($id, $title, $slug, $desc, $actions);
  }
  
  public function validate($values)
  {
    if($values['slug'] && !preg_match('/^[a-z\-]+$/i', $values['slug'])){
      $this->error('slug', 'Invalid URL slug');
    }
  }
  
  public function submit($values)
  {
    $col = new stdClass;
    $col->id = $values['id'];
    $col->title = $values['title'];
    $col->slug = strtolower($values['slug']);
    $col->description = $values['description'];
    
    $model = new CollectionModel();
    $model->save($col);
    
    if(!$values['id']){
      $this->message('Collection added');
      $http = new Http;
      $http->redirect('collections');
    } else {
      $this->message('Collection saved');
    }
  }
}
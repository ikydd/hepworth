<?php

class Collections extends AdminController
{
  public function index()
  {
    return $this->listing();
  }
  
  protected function setTabs($active, $id = null)
  {
    $this->tabs = array();
    
    $this->tabs[] = (object) array(
      'url' => $this->http->url('collections'), 
      'title' => 'List', 
      'active' => 'collections' == $active
    );
    $this->tabs[] = (object) array(
      'url' => $this->http->url('collections/add'), 
      'title' => 'Add', 
      'active' => 'collections/add' == $active
    );
    
    if($id){
      $this->tabs[] = (object) array(
        'url' => $this->http->url('collections/edit/' . $id), 
        'title' => 'Edit', 
        'active' => 'collections/edit/' . $id == $active
       );
      $this->tabs[] = (object) array(
        'url' => $this->http->url('collections/delete/' . $id), 
        'title' => 'Delete', 
        'active' => 'collections/delete/' . $id == $active
      );
    }
  }
  
  public function listing()
  {
    // $this->setTitle('Collections');
    
    $model = new CollectionModel;
    $collections = $model->findWhere(array(), array('position' => 'ASC'));

    $form = new ReorderCollectionsForm($collections);
    $view = new View();
    
    $this->setTabs('collections');
    
    $content = '';
    $content .= $form->render();    
    
    return $this->render($content, 'topmenu');
  }
  
  public function add()
  {
    // $this->setTitle('Add Collection');
    $form = new AddEditCollectionForm();

    $this->setTabs('collections/add');
    
    $content = '';
    $content .= $form->render();
    
    return $this->render($content, 'topmenu');
  }
  
  public function edit($id)
  {
    $model = new CollectionModel;
    $collection = $model->find($id);
    
    if(!$collection){
      $this->feedback->error('Could not find that collection');
      $this->http->redirect('collections');
    }
    
    // $this->setTitle('Edit Collection');
    $form = new AddEditCollectionForm($collection);
    
    $this->setTabs('collections/edit/' . $id, $id);
    
    $content = '';
    $content .= $form->render();
    
    $model = new PictureModel;
    $pictures = $model->findWhere(array('collection_id' => $collection->id), array('position' => 'asc'));
    
    $form = new ReorderCollectionForm($pictures);
    
    $content .= '<hr />';
    $content .= '<h4>Reorder Pictures</h4>';
    $content .= $form->render();
    
    return $this->render($content, 'topmenu');
  }
  
  public function delete($id)
  {
    $model = new CollectionModel;
    $collection = $model->find($id);
    
    if(!$collection){
      $this->feedback->error('Could not find that collection');
      $this->http->redirect('collections');
    }

    // $this->setTitle('Delete Collection');
    $form = new DeleteCollectionForm($collection);
    
    $this->setTabs('collections/delete/' . $id, $id);
    
    $content = '';
    $content .= $form->render();
    
    return $this->render($content, 'topmenu');
  }
}
<?php

class Pictures extends AdminController
{
  public function index()
  {
    return $this->listing();
  }
  
  protected function setTabs($active, $id = null)
  {
    $this->tabs = array();
    
    $this->tabs[] = (object) array(
      'url' => $this->http->url('pictures'), 
      'title' => 'List', 
      'active' => 'pictures' == $active
    );
    $this->tabs[] = (object) array(
      'url' => $this->http->url('pictures/add'), 
      'title' => 'Add', 
      'active' => 'pictures/add' == $active
    );
    
    if($id){
      $this->tabs[] = (object) array(
        'url' => $this->http->url('pictures/edit/' . $id), 
        'title' => 'Edit', 
        'active' => 'pictures/edit/' . $id == $active
       );
      $this->tabs[] = (object) array(
        'url' => $this->http->url('pictures/delete/' . $id), 
        'title' => 'Delete', 
        'active' => 'pictures/delete/' . $id == $active
      );
    }
  }
  
  public function listing()
  {
   // $this->setTitle('Pictures');
    
    $model = new PictureModel;
    $pictures = $model->findWhere(array(), array('title' => 'ASC'));
    
    $view = new View();
    
    $rows = array();
    foreach($pictures as $picture){
      $actions = array(
        $view->show('portfolio/icon-action-link', array('icon' => 'pencil', 'url' => $this->http->url('pictures/edit/' . $picture->id))),
        $view->show('portfolio/icon-action-link', array('icon' => 'trash', 'url' => $this->http->url('pictures/delete/' . $picture->id)))
      );
      $rows[] = array(
        '<img src="' . $this->http->url($picture->mini) . '" />',
        $picture->title,
        $picture->caption,
        implode(' &nbsp; ', $actions)
      );
    }
    
    $header = array(
      '&nbsp;',
      'Title',
      'Caption',
      '&nbsp;'
    );
    
    $this->setTabs('pictures');
    
    $content = '';
    $content .= $view->show('standard/table-simple', array(
      'rows' => $rows,
      'header' => $header,
      'empty' => 'You have no pictures yet'
    ));
    
    
    return $this->render($content, 'topmenu');
  }
  
  public function add()
  {
    // $this->setTitle('Add Picture');
    $form = new AddEditPictureForm();

    $this->setTabs('pictures/add');
    
    $content = '';
    $content .= $form->render();
    
    return $this->render($content, 'topmenu');
  }
  
  public function edit($id)
  {
    $model = new PictureModel;
    $picture = $model->find($id);
    
    if(!$picture){
      $this->feedback->error('Could not find that picture');
      $this->http->redirect('pictures');
    }
    
    // $this->setTitle('Edit Picture');
    $form = new AddEditPictureForm($picture);
    
    $this->setTabs('pictures/edit/' . $id, $id);
    
    $content = '';
    $content .= $form->render();
    
    return $this->render($content, 'topmenu');
  }
  
  public function delete($id)
  {
    $model = new PictureModel;
    $picture = $model->find($id);
    
    if(!$picture){
      $this->feedback->error('Could not find that picture');
      $this->http->redirect('pictures');
    }

    // $this->setTitle('Delete Picture');
    $form = new DeletePictureForm($picture);
    
    $this->setTabs('pictures/delete/' . $id, $id);
    
    $content = '';
    $content .= $form->render();
    
    return $this->render($content, 'topmenu');
  }
}
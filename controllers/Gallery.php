<?php

class Gallery extends BaseController
{
  protected $gallery;
  
  public function index($slug = null)
  {
    $model = new CollectionModel();
    $cols = $model->findWhere(null, array('position' => 'ASC'));
    
    if(count($cols) == 0){
      return $this->render('No pictures to show');
    }
    
    return $this->show($cols[0]->slug);
  }
  
  public function show($slug)
  {
    $model = new CollectionModel;
    $col = $model->findBySlug($slug);
    
    if(!$col) return $this->render('Cannot find that portfolio');
    
    $this->gallery = $col->slug;
    
    $picMod = new PictureModel;
    $pictures = $picMod->findWhere(array('collection_id' => $col->id), array('position' => 'ASC'));
    
    $view = new View();
    
    $this->setTitle($col->title);
    
    $content = '';
    $content .= $view->show('portfolio/collection', array('collection' => $col, 'pictures' => $pictures));
    
    return $this->render($content);
  }
}
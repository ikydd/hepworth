<?php

class Exhibitions extends BaseController
{ 
  public function index()
  {
    $model = new PageModel;
    $page = $model->find('exhibitions');
    
    $this->setTitle($page->title);
    
    $view = new View();
    
    $content = '';
    $content .= $view->show('portfolio/page', array('title' => $page->title, 'content' => nl2br($page->content)));
    
    return $this->render($content);
  }
}
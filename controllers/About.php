<?php

class About extends BaseController
{  
  public function index()
  {
    $model = new PageModel;
    $page = $model->find('about');
    
    $this->setTitle($page->title);
    
    $view = new View();
    
    $sModel = new SettingModel;
    $img = $sModel->get('about-image');
    
    $content = '';
    $content .= $view->show('portfolio/about', array('title' => $page->title, 'content' => nl2br($page->content), 'image' => $img));
    
    return $this->render($content);
  }
}
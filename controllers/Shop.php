<?php

class Shop extends BaseController
{ 
  public function index()
  {
    $model = new PageModel;
    $page = $model->find('shop');
    
    $this->setTitle($page->title);
    
    $model = new SettingModel;
    
    $view = new View();
    
    $content = '';
    $content .= $view->show('portfolio/shop', array(
      'title' => $page->title, 
      'url' => $model->get('shop-url'), 
      'url_text' => $model->get('shop-url-text') ? $model->get('shop-url-text') : $model->get('shop-url'), 
      'content' => nl2br($page->content)));
    
    return $this->render($content);
  }
}
<?php

class Prints extends BaseController
{
  public function index()
  {
    $model = new PictureModel;
    $prints = $model->findWhere(array('available' => 1), array('title' => 'ASC'));
    
    $pModel = new PageModel;
    $page = $pModel->find('prints');
    
    $this->load('Money');
    
    foreach($prints as $print){
      $print->price_formatted = $this->money->format($print->price);
    }
    
    $view = new View();
    
    $this->setTitle($page->title);
    
    $settings = new SettingModel;
    
    $content = '';
    $content .= $view->show('portfolio/prints', array(
      'intro' => nl2br($page->content), 
      'link' => $view->show('portfolio/prints-about-link'),
      'prints' => $prints, 
      'paypal' => $settings->get('paypal-account')));
    
    return $this->render($content);
  }
  
  public function about()
  {
    $model = new PageModel;
    $page = $model->find('print-info');
    
    $this->setTitle($page->title);
    
    $view = new View();
    
    $content = '';
    $content .= $view->show('portfolio/page', array('title' => $page->title, 'content' => nl2br($page->content)));
    $content .= $view->show('portfolio/prints-terms-link');
    
    return $this->render($content);
  }
  
  public function terms()
  {
    $model = new PageModel;
    $page = $model->find('terms');
    
    $this->setTitle($page->title);
    
    $view = new View();
    
    $content = '';
    $content .= $view->show('portfolio/page', array('title' => $page->title, 'content' => nl2br($page->content)));
    
    return $this->render($content);
  }
  
  public function thanks()
  {
    $model = new PageModel;
    $page = $model->find('print-thanks');
    
    $this->setTitle($page->title);
    
    $view = new View();
    
    $content = '';
    $content .= $view->show('portfolio/page', array('title' => $page->title, 'content' => nl2br($page->content)));
    
    return $this->render($content);
  }
}
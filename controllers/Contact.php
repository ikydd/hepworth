<?php

class Contact extends BaseController
{
  public function index()
  {
    $model = new PageModel;
    $page = $model->find('contact');
    
    $this->setTitle($page->title);
    
    $view = new View();
    
    $content = '';
    $content .= $view->show('portfolio/page', array('title' => $page->title, 'content' => nl2br($page->content)));
    
    $contactform = new ContactForm;
    
    $content .= $view->show('portfolio/contact', array('contactForm' => $contactform->render()));
    
    return $this->render($content);
  }
  
  public function thanks()
  {
    $model = new PageModel;
    $page = $model->find('contact-thanks');
    
    $this->setTitle($page->title);
    
    $view = new View();
    
    $content = '';
    $content .= $view->show('portfolio/page', array('title' => $page->title, 'content' => nl2br($page->content)));
    
    return $this->render($content);
  }
}
<?php

class Words extends BaseController
{
  private static $files = array(
    'about' => 'public/html/about.html',
    'prints' => 'public/html/prints.html',
    'terms' => 'public/html/terms.html'
  );
  
  private function getForm($key)
  {
    $model = new PageModel;
    $page = $model->find($key);
    
    $form = new EditPageForm($page);
    
    return $form->render();
  }

  protected function setTabs($active, $id = null)
  {
    $this->tabs = array();
    
    $this->tabs[] = (object) array(
      'url' => $this->http->url('words/prints'), 
      'title' => 'Prints', 
      'active' => 'prints' == $active
    );
    $this->tabs[] = (object) array(
      'url' => $this->http->url('words/printinfo'), 
      'title' => 'Print Info', 
      'active' => 'printinfo' == $active
    );
    $this->tabs[] = (object) array(
      'url' => $this->http->url('words/printthanks'), 
      'title' => 'Print Thanks', 
      'active' => 'printthanks' == $active
    );
    $this->tabs[] = (object) array(
      'url' => $this->http->url('words/terms'), 
      'title' => 'Terms', 
      'active' => 'terms' == $active
    );
    $this->tabs[] = (object) array(
      'url' => $this->http->url('words/about'), 
      'title' => 'About', 
      'active' => 'about' == $active
    );
    $this->tabs[] = (object) array(
      'url' => $this->http->url('words/contact'), 
      'title' => 'Contact', 
      'active' => 'contact' == $active
    );
    $this->tabs[] = (object) array(
      'url' => $this->http->url('words/contactthanks'), 
      'title' => 'Contact Thanks', 
      'active' => 'contactthanks' == $active
    );
    $this->tabs[] = (object) array(
      'url' => $this->http->url('words/exhibitions'), 
      'title' => 'Exhibitions', 
      'active' => 'exhibitions' == $active
    );
    $this->tabs[] = (object) array(
      'url' => $this->http->url('words/shop'), 
      'title' => 'Shop', 
      'active' => 'shop' == $active
    );
  }
  
  public function index()
  {
    return $this->about();
  }
  
  public function prints()
  {
    $content = '';
    
    $view = new View;
    $content .= $view->show('portfolio/page-view-link', array('url' => 'prints'));
    
    $this->setTabs('prints');
    
    $model = new PageModel;
    $page = $model->find('prints');
    
    $form = new EditPrintsPageForm($page);
    $content .= $form->render();
    
    return $this->render($content, 'topmenu');
  }
  
  public function printinfo()
  {
    $content = '';
    
    $view = new View;
    $content .= $view->show('portfolio/page-view-link', array('url' => 'prints/about'));
    $content .= $this->getForm('print-info');
    
    $this->setTabs('printinfo');
    
    return $this->render($content, 'topmenu');
  }
  
  public function printthanks()
  {
    $content = '';
    
    $view = new View;
    $content .= $view->show('portfolio/page-view-link', array('url' => 'prints/thanks'));
    $content .= $this->getForm('print-thanks');
    
    $this->setTabs('printthanks');
    
    return $this->render($content, 'topmenu');
  }
  
  public function terms()
  {
    $content = '';
    
    $view = new View;
    $content .= $view->show('portfolio/page-view-link', array('url' => 'prints/terms'));
    $content .= $this->getForm('terms');
    
    $this->setTabs('terms');
    
    return $this->render($content, 'topmenu');
  }
  
  public function about()
  {
    $content = '';
    
    $view = new View;
    $content .= $view->show('portfolio/page-view-link', array('url' => 'about'));
    
    $model = new PageModel;
    $page = $model->find('about');
    
    $form = new EditAboutPageForm($page);
    $content .= $form->render();
    
    $this->setTabs('about');
    
    return $this->render($content, 'topmenu');
  }
  
  public function contact()
  {
    $content = '';
    
    $view = new View;
    $content .= $view->show('portfolio/page-view-link', array('url' => 'contact'));
    $content .= $this->getForm('contact');
    
    $this->setTabs('contact');
    
    return $this->render($content, 'topmenu');
  }
  
  public function contactthanks()
  {
    $content = '';
    
    $view = new View;
    $content .= $view->show('portfolio/page-view-link', array('url' => 'contact/thanks'));
    $content .= $this->getForm('contact-thanks');
    
    $this->setTabs('contactthanks');
    
    return $this->render($content, 'topmenu');
  }
  
  public function exhibitions()
  {
    $content = '';
    
    $view = new View;
    $content .= $view->show('portfolio/page-view-link', array('url' => 'exhibitions'));
    
    $model = new PageModel;
    $page = $model->find('exhibitions');
    
    $form = new EditExhibitionsPageForm($page);
    $content .= $form->render();
    
    $this->setTabs('exhibitions');
    
    return $this->render($content, 'topmenu');
  }
  
  public function shop()
  {
    $content = '';
    
    $view = new View;
    $content .= $view->show('portfolio/page-view-link', array('url' => 'shop'));
    
    $model = new PageModel;
    $page = $model->find('shop');
     
    $form = new EditShopPageForm($page);
    $content .= $form->render();
    
    $this->setTabs('shop');
    
    return $this->render($content, 'topmenu');
  }
}
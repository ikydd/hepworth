<?php

class Engine
{
  private $router;
  private $controller;
  
  public function __construct()
  {
    $site = new Sitepath();
  }

  public function manufacture($url)
  {
    $this->router = new Router($url);
    
    $content = $this->body();
    // here we could just echo out the content to the browser like this
    // echo $content;
    // but we'll be more sneaky, what we'll do is take our content and stick in the middle of
    // a proper HTML page with <head> tags and all that shit
    return $this->layout($content);
  }
  
  private function body()
  {
    // we call the required function here
    $class = $this->router->getController();
    $this->controller = new $class();
    return call_user_func_array(array($this->controller, $this->router->getPage()), $this->router->getArguments());
  }
  
  private function layout($content)
  {
    // getting the layout from the controllers allows us to set the layout in the page method
    // if we want a different layout
    $controller = get_class($this->controller);
    $layout = $this->controller->getLayout();
    $id = $this->controller->getPageID();
    if(!$id) $id = strtolower(implode('-', $this->router->getSegments()));
    $class = strtolower($controller . '-' . $this->router->getPage());
    $title = $this->controller->getTitle();
    if(!$title) $title = $controller;
    $canonical = $this->router->getCanonical();
    
    $view = new View();
    return $view->show($layout, array(
      'id' => $id, 
      'class' => $class, 
      'content' => $content, 
      'title' => $title, 
      'canonical' => $canonical
    ));
  }
}
<?php

abstract class BaseController extends Controller
{
  protected $title;
  protected $nav;
  protected $tabs;
  
  public function __construct()
  {
    $this->load('Http');
    $this->load('Includes');
    $this->load('Path');
    
    $this->includes->css($this->http->url($this->path->css('bootstrap.min.css')));
    $this->includes->css($this->http->url($this->path->css('lightbox.css')));
    $this->includes->css($this->http->url($this->path->css('font-awesome.min.css')));
    $this->includes->css($this->http->url($this->path->css('style.css')));
    
    $this->includes->js($this->http->url($this->path->js('jquery.min.js')));
    $this->includes->js($this->http->url($this->path->js('jquery-ui.min.js')));
    $this->includes->js($this->http->url($this->path->js('jquery.noconflict.js')));
    $this->includes->js($this->http->url($this->path->js('bootstrap.min.js')));
    $this->includes->js($this->http->url($this->path->js('prototype.js')));
    $this->includes->js($this->http->url($this->path->js('scriptaculous.js?load=effects')));
    $this->includes->js($this->http->url($this->path->js('lightbox.js')));
    $this->includes->js($this->http->url($this->path->js('offset.js')));
    
    $this->includes->js(array('basePath' => $this->http->url()), 'setting');
    
    $this->persistentLogin();
    
    parent::__construct();
  }
  
  protected function persistentLogin()
  {
    if(!Auth::isLoggedIn()){
    
      // check for cookies
      $ident = isset($_COOKIE['ident']) ? $_COOKIE['ident'] : false;
      $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : false;
        
      $clean = array();
        
      // clean the cookies
      $clean['ident'] = ctype_alnum($ident) ? $ident : '';
      $clean['token'] = ctype_alnum($token) ? $token : '';
      
      if(!$clean['ident'] && !$clean['token']) return false;
      
      try{
        if(!$clean['ident'] || !$clean['token']) throw new Exception();
        
        $model = new UserModel();
        $users = $model->findWhere(array('ident' => $clean['ident']));
        
        if(count($users) == 0) throw new Exception();
        
        $user = $users[0];
      
        $auth = new Auth();
        if($auth->authenticate('persistent', $user, $clean['token'])){
          $user->accept(new LoginPassVisitor);
          $user->accept(new RememberMeVisitor);
          Auth::storeUser($user);
        }
      } catch(Exception $e){
        // delete cookies  time()-60*60*24*30
        setcookie('ident', 'DELETED', time()+30, '/');
        setcookie('token', 'DELETED', time()+30, '/');
      }
    }
  }
  
  protected function render($content, $type = 'sidebar')
  {
    $view = new View();
   
    $header = $view->show('portfolio/header');
    $title = $this->title ? $view->show('portfolio/title', array('title' => $this->title)) : '';
    $parts = explode('/', $this->http->current());
    $settings = new SettingModel();
    
    switch($type){
      case 'sidebar':
      
        $links = array(
          'twitter' => ($tw = $settings->find('twitter-page')) ? $tw->value : '',
          'facebook-square' => ($fb = $settings->find('facebook-page')) ? $fb->value : '',
          'tumblr-square' => ($pi = $settings->find('tumblr-page')) ? $pi->value : '',
          'instagram' => ($in = $settings->find('instagram-page')) ? $in->value : '',
        );
        
        $social = $view->show('portfolio/social-links', array('links' => $links));
        $menu = $view->show('portfolio/menu', array('items' => $this->menuLinks(), 'social' => $social));
        
        return $view->show('layouts/sidebar', array(
          'menu' => $menu, 
          'header' => $header, 
          'content' => $content,
          'title' => $title
        ));
        
      case 'plain':
        
        return $view->show('layouts/plain', array(
          'header' => $header, 
          'content' => $content,
          'title' => $title
        ));
        
      case 'topmenu':
    
        if($this->tabs){
          $content = $view->show('standard/tabs', array('tabs' => $this->tabs)) . $content;
        }

        $this->nav = Auth::isLoggedIn() ? $view->show('portfolio/navigation', array('links' => $this->adminLinks())) : '';
        
        return $view->show('layouts/topmenu', array(
          'navigation' => $this->nav, 
          'header' => $header, 
          'content' => $content,
          'title' => $title
        ));
        
      default:
        throw new Exception('Invalid layout selected');
    }
  }
  
  protected function menuLinks()
  {
    $model = new CollectionModel();
    $collections = $model->findWhere(null, array('position' => 'asc'));
   
    
    $model = new SettingModel;
    $exhibit = $model->get('show-exhibitions');
    $prints = $model->get('show-prints');
    $shopUrl = $model->get('shop-url');
    $shop = $model->get('show-shop');
    
    $items = array();
    foreach($collections as $collection){
      $items[] = (object) array(
        'title' => $collection->title,
        'url' => $this->http->url('gallery/' . $collection->slug),
        'active' => get_class($this) == 'Gallery' && $this->gallery == $collection->slug
      );
    }
    if($prints){
      $items[] = (object) array(
        'title' => 'Prints',
        'url' => $this->http->url('prints'),
        'active' => get_class($this) == 'Prints'
      );
    }
    if($shop){
      $items[] = (object) array(
        'title' => 'Shop',
        'url' => $shopUrl,
        'active' => get_class($this) == 'Shop'
      );
    }
    if($exhibit){
      $items[] = (object) array(
        'title' => 'Exhibitions',
        'url' => $this->http->url('exhibitions'),
        'active' => get_class($this) == 'Exhibitions'
      );
    }
    $items[] = (object) array(
      'title' => 'About',
      'url' => $this->http->url('about'),
      'active' => get_class($this) == 'About'
    );

    // $items[] = (object) array(
      // 'title' => 'Contact',
      // 'url' => $this->http->url('contact'),
      // 'active' => get_class($this) == 'Contact'
    // );

    if(Auth::isLoggedIn()){
      $items[] = (object) array(
        'title' => 'Admin',
        'url' => $this->http->url('collections'),
        'active' => false
      );
    }
    
    return $items;
  }
  
  protected function adminLinks()
  {
    $links = array();
    
    $links[] = (object) array(
      'active' => get_class($this) == 'Collections',  
      'url' => $this->http->url('collections'), 
      'title' => 'Collections'
    );
    $links[] = (object) array(
      'active' => get_class($this) == 'Pictures',
      'url' => $this->http->url('pictures'), 
      'title' => 'Pictures'
    );
    $links[] = (object) array(
      'active' => get_class($this) == 'Words',
      'url' => $this->http->url('words'),
      'title' => 'Words'
    );
    $links[] = (object) array(
      'active' => get_class($this) == 'Profile',
      'url' => $this->http->url('profile'), 
      'title' => 'Profile'
    );
    $links[] = (object) array(
      'active' => get_class($this) == 'Settings',
      'url' => $this->http->url('settings'),
      'title' => 'Settings'
    );
    $links[] = (object) array(
      'active' => get_class($this) == 'Logout',
      'url' => $this->http->url('logout'),
      'title' => 'Logout'
    );
    
    return $links;
  }
}
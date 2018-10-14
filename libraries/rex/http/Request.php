<?php

/**
 *  Class Rex\HTTP\Request
 *
 *  A little class for doing all those curl requests that you always forget to write
 *  a class for. Just does POST and GET, but handles auth and accepts headers too.
 *
 *  outputs a SimpleHTTPResponse class. This gives the content to string, but has 
 *  all the other information on it too. It also handles the JSON decoding too
 *
 *  Public methods:
 *  reset
 *  auth
 *  accepts
 *  post
 *  get
 *
 *  Sample usage:
 *
 *  $http = new SimpleHTTP();
 *  $response = $http->accepts('json')
 *                    ->auth('username', 'password', 'basic')
 *                    ->post('someurl.php', array('a' => '123'));
 *
 *  if($response->ok()) echo $response;
 *
 *  if($response->ok() && $response->decodes()) $json = $response->json();
 */
 
namespace Rex\HTTP;

class Request
{
  protected $domain;
  protected $options = array();
  protected $headers = array();
  
  public function __construct()
  {
    $this->reset();
  }
  
  // sets the CURL options back to defaults
  public function reset()
  {
    $this->options = array(
      CURLOPT_HEADER => 0,
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_TIMEOUT => 10,
      CURLOPT_FOLLOWLOCATION => 1
    );
    
    return $this;
  }
  
  // converts array to proper request string
  protected function formatData($data = null)
  {
    if(is_null($data)){
      return null;
    } else if(is_array($data)){
      return http_build_query($data, '', '&');
    } else {
      return $data;
    }
  }
  
  // takes header shortcut and sorts it out
  protected function acceptHeader($type)
  {
   switch($type){
      case 'json':
        return 'Accept: application/json';
      case 'xml':
        return 'Accept: text/xml';
      case 'text':
        return 'Accept: text/plain';
     case 'html':
        return 'Accept: text/html';
      default:
        return 'Accept: text/html';
    }
  }
  
  // shortcut for settings the accepts header
  public function accept($type = false)
  {
    $type = strtolower($type);
    
    if(in_array($type, array('json', 'xml', 'html', 'text'))) {
      $this->headers[] =  $this->acceptHeader($type);
   }
    
    return $this;
  }
  
  // manually set some headers - takes array or string
  public function headers($header = array())
  {
    if(is_array($headers)) {
      $this->headers = array_merge($this->headers, $header);
    } else {
      $this->headers[] = $header;
    }
    
    return $this;
  }
  
  // accepts auth paramters and picks a type
  public function auth($user, $pass)
  {
    $this->options[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
    $this->options[CURLOPT_USERPWD] = $user . ':' . $pass;
    
    return $this;
  }
  
  // make sure it's ok for SSL
  public function ssl()
  {
    $this->options[CURLOPT_SSL_VERIFYPEER] = 0;
    
    return $this;
  }
  
  // should trick an app into thinking it's an AJAX request
  public function ajax()
  {
    $this->headers[] = 'X-Requested-With: XMLHttpRequest';
    
    return $this;
  }
  
  // set up connection with options array
  protected function setup()
  {
    $this->conn = curl_init();
    $this->addHeaders();
    $this->setOptions($this->options);
  }
  
  protected function addHeaders()
  {
    $this->options[CURLOPT_HTTPHEADER] = $this->headers;
    // clear the array for next time
    $this->headers = array();
  }
  
  protected function setOptions($options)
  {
    curl_setopt_array($this->conn, $options);
  }
  
  protected function setUrl($url)
  {
    curl_setopt($this->conn, CURLOPT_URL, $url);
  }
  
  protected function getResponse($conn)
  {
    $response = new Response();
    $response->content = curl_exec($this->conn);
    // if no response is given, check the errors and attach to response
    if ($response->content === false) {
      $response->errorMsg = curl_error($this->conn);
    }
    $response->info = curl_getinfo($this->conn);
    
    return $response;
  }
  
  // actually execute the request
  protected function send($url)
  {    
    $this->setup();
    
    $this->setUrl($url);
    
    $response = $this->getResponse($this->conn);
    
    curl_close($this->conn);
    
    $this->reset();
    
    return $response;
  }
  
  public function request($method, $url, $data)
  {
    $method = strtoupper($method);
    $data = $this->formatData($data);
    switch($method){
      case 'POST':
        $this->post($data);
        break;
      case 'GET':
        $url = $url . '?' . $data;
        $this->get();
        break;
      case 'PUT':
      case 'DELETE':
      case 'OPTIONS':
      default:
        $this->custom($method, $data);
        break;
    }
    
    return $this->send($url);
  }
  
  // make a get request with parameters
  protected function get()
  {
    $this->options[CURLOPT_HTTPGET] = 1;
  }
  
  // make a post request, with fields
  protected function post($data = null)
  {
    $this->options[CURLOPT_POST] = 1;
    $this->options[CURLOPT_POSTFIELDS] = $data;
  }
 
  // make a put request, with fields
  protected function custom($method, $data = null)
  {
    $this->options[CURLOPT_CUSTOMREQUEST] = $method;
    $this->options[CURLOPT_POSTFIELDS] = $data;
  }
}
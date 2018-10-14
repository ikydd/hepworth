<?php

namespace Rex\HTTP;

class ReusableRequest extends Request
{
  public function __construct($domain = '')
  {
    $this->domain = $domain;
    parent::__construct();
  }
  
  // sets the CURL options back to defaults
  public function reset()
  {
    unset($this->options[CURLOPT_POSTFIELDS]);
    unset($this->options[CURLOPT_POST]);
    unset($this->options[CURLOPT_HTTPGET]);
    unset($this->options[CURLOPT_CUSTOMREQUEST]);
    
    $this->options[CURLOPT_HEADER] = 0;
    $this->options[CURLOPT_RETURNTRANSFER] = 1;
    $this->options[CURLOPT_TIMEOUT] = 10;
    $this->options[CURLOPT_FOLLOWLOCATION] = 1;
    
    return $this;
  }
  
  protected function setUrl($url)
  {
    curl_setopt($this->conn, CURLOPT_URL, $this->domain . $url);
  }
  
  protected function addHeaders()
  {
    if(!empty($this->headers)){
      $this->options[CURLOPT_HTTPHEADER] = $this->headers;
    }
    // clear the array for next time
    $this->headers = array();
  }
}
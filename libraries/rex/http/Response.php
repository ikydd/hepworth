<?php

/**
 *  Class SimpleHTTPResponse
 *
 */
 
namespace Rex\HTTP;

class Response
{
  protected $xmlContent;
  protected $jsonContent;
  public $content;
  public $errorMsg;
  public $info = array();
  
  public function __toString()
  {
    return (string) $this->content;
  }
  // BASIC CHECKS
  public function ok()
  {
    return $this->status() == '200';
  }
  
 public function status()
  {
    return $this->info['http_code'];
  }
  public function error()
  {
    return $this->errorMsg;
  }
  
  // JSON
  public function json()
  {
    if(!isset($this->jsonContent)) {
      $this->jsonContent = json_decode($this->content);
      // set error if can't decode and if it's ok (otherwise we'll have another error)
      if($this->ok() && is_null($this->jsonContent)){
        $this->errorMsg = 'Could not decode JSON';
      }
    }
    return $this->jsonContent;
  }
  
  
  // XML
  public function xml()
  {
    if(!isset($this->xmlContent)) {
      // XML element throws exception when fails
      try{
        $this->xmlContent = new SimpleXMLElement($this->content);
      } catch(Exception $e){
        $this->xmlContent = null;
        // set error msg if can't parse and if it's ok (otherwise we'll have another error)
        if($this->ok() && is_null($this->xmlContent)){
          $this->errorMsg = 'Could not parse XML';
        }
      }
    }
    return $this->xmlContent;
  }
}
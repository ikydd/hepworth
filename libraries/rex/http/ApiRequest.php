<?php

class ApiRequest
{
  private $requestMethod;
  private $rawData;
  private $json;
  private $xml;
  private $fields;
  
  public function __construct()
  {
    $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    $this->collectIncomingData();
    // $this->parseData();
  }
  
  public function getRequestMethod()
  {
    return $this->requestMethod;
  }
  
  private function collectIncomingData()
  {
    switch(strtoupper($this->getRequestMethod())){
      case 'GET':
        $this->fields = $_GET;
      case 'PUT':
      case 'DELETE':
      case 'OPTIONS':
      case 'POST':
        $this->rawData = trim(file_get_contents('php://input'));
      default:
        break;
    }
  }
  
  private function parseData()
  {
    $this->parseXML($this->getRawData());
    $this->parseJSON($this->getRawData());
    if(!$this->getFields()){
      $this->parseFields($this->getRawData());
    }
  }
  
  private function parseXML($data)
  {
    try{
      $this->xml = new SimpleXMLElement($data);
    } catch(Exception $e){
      // do nothing?
    }
  }
  
  private function parseJSON()
  {
    $this->json = json_decode($data);
  }
  
  private function parseFields($data)
  {
    parse_str($data, $this->fields);
  }
  
  public function getIP()
  {
    return $_SERVER['REMOTE_ADDR'];
  }
  
  public function getUrl()
  {
    return $_SERVER['REQUEST_URI'];
  }
  
  public function getRawData()
  {
    return $this->rawData;
  }
  
  public function getJSON()
  {
    return $this->json;
  }
  
  public function getXML()
  {
    return $this->xml;
  }
  
  public function getFields()
  {
    return $this->fields;
  }
}
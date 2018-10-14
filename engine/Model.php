<?php

/*
 *  The model part of MVC is very vague and it all happens differently in different 
 *  
 */

abstract class Model
{
  // this is empty right now as
  protected $tableName;
  protected $primaryKey = 'id';
  protected $database = 'default';
  protected $entity = 'Entity';
  protected $itemsPerPage = 15;
  
  public function __construct()
  {
    $this->db = Database::connect($this->database);
  }
  
  public function setItemsPerPage($items)
  {
    $this->itemsPerPage = $items;
  }
  
  protected function generatePlaceholders($len)
  {
    static $i = 0;
    
    $j = $i;
    
    $holders = array();
    for($i; $i < ($j + $len); $i++){
      $holders[] = ':placeholder_' . $i;
    }
    return $holders;
  }
  
  public function create($obj)
  {
    $vars = get_object_vars($obj);
    
    $sql = "INSERT INTO `{$this->tableName}` ";
    
    $count = count($vars);
    $holders = $this->generatePlaceholders($count);
    
    if($count){
      $ks = array();
      foreach(array_keys($vars) as $k){
        $ks[] = "`{$k}`";
      }
      $sql .= "(" . implode(', ', $ks) . ") VALUES (" . implode(', ', $holders) . ")";
    }
    
    $stm = $this->db->prepare($sql);
    $res = $stm->execute(array_combine($holders, array_values($vars)));
    
    if(!$res) throw new Exception('Failed to save');
    
    $id = $this->db->lastInsertId();
        
    return $this->find($id);
  }
  
  public function update($obj)
  {
    $vars = get_object_vars($obj);
    
    if(!isset($vars[$this->primaryKey]) || !$vars[$this->primaryKey]) throw new Exception('Update request has no ID');
    
    $id = $vars[$this->primaryKey];
    unset($vars[$this->primaryKey]);
    
    $count = count($vars);
    if(!$count) throw new Exception('Update request does not include any fields');
    
    $holders = $this->generatePlaceholders($count);
    
    $fields = array();
    $i = 0;
    foreach($vars as $k => $v){
      $fields[] = "`{$k}` = {$holders[$i]}";
      $i++;
    }
    
    $sql = "UPDATE `{$this->tableName}` SET " .implode(', ', $fields) . " WHERE `{$this->primaryKey}` = :id"; 
    
    $ex = array_combine($holders, array_values($vars));
    $ex[':id'] = $id;
    
    $stm = $this->db->prepare($sql);
    $res = $stm->execute($ex);
    
    if(!$res) return false;
    
    return $this->find($id);
  }
  
  public function save($obj)
  {
    $id = $this->primaryKey;
    if(isset($obj->{$id}) && $obj->{$id}){
      return $this->update($obj);
    } else {
      return $this->create($obj);
    }
  }
  
  // this will load a row from the table onto an object for us if we give it an id
  public function find($id)
  {
    if(!$id) return false;
    
    $stm = $this->db->prepare("SELECT * FROM `{$this->tableName}` WHERE `{$this->primaryKey}` = :id");
    $stm->execute(array(':id' => $id));
    
    return $stm->fetchObject($this->entity);
  }
    
  public function findWhere(array $conditions = null, array $order = null, $limit = null, $offset = 0)
  {
    $sql = "SELECT * FROM `{$this->tableName}`";
    $ex = array();
    
    if($conditions && count($conditions)){

      $holders = $this->generatePlaceholders(count($conditions));
      
      $fields = array();
      $i = 0;
      foreach($conditions as $k => $v){
        $fields[] = "`{$k}` = {$holders[$i]}";
        $i++;
      }
      $ex = array_combine($holders, array_values($conditions));
      $sql .= " WHERE " . implode(' AND ', $fields);
    }
    
    if($order && count($order)){

      $holders = $this->generatePlaceholders(count($order));
      
      $fields = array();
      $i = 0;
      foreach($order as $k => $v){
        $fields[] = "`{$k}` {$v}";
        $i++;
      }
      $sql .= " ORDER BY " . implode(', ', $fields);
    }
    
    if($limit){
      $sql .= " LIMIT {$offset}, {$limit}";
    }
    
    $stm = $this->db->prepare($sql);
    $stm->execute($ex);
    
    return $stm->fetchAll(PDO::FETCH_CLASS, $this->entity);
  }
  
  public function countWhere(array $conditions = null)
  {
    $sql = "SELECT COUNT(*) AS total FROM `{$this->tableName}`";
    $ex = array();
    
    if($conditions && count($conditions)){

      $holders = $this->generatePlaceholders(count($conditions));
      
      $fields = array();
      $i = 0;
      foreach($conditions as $k => $v){
        $fields[] = "`{$k}` = {$holders[$i]}";
        $i++;
      }
      $ex = array_combine($holders, array_values($conditions));
      $sql .= " WHERE " . implode(' AND ', $fields);
    }
    
    $stm = $this->db->prepare($sql);
    $stm->execute($ex);
    
    return $stm->fetchAll(PDO::FETCH_CLASS, $this->entity);
  }
  
  public function pagedWhere($page = 1, array $conditions = null, array $order = null)
  {
    if($page < 1) $page = 0;
    else $page--;
    $count = $this->countWhere($conditions);
    
    $offset = $this->itemsPerPage * $page;
    $limit = $this->itemsPerPage;
    return $this->findWhere($conditions, $order, $limit, $offset);
  }
  
  // this will delete an object from the table
  public function delete($id)
  {
    if(!$id) throw new Exception('No ID passed into a delete command');
    
    $stm = $this->db->prepare("DELETE FROM `{$this->tableName}` WHERE `{$this->primaryKey}` = :id");
    $stm->execute(array(':id' => $id));
    
    return true;
  }
  
  // this will load a row from the table onto an object for us if we give it an id
  public function findMany(array $ids)
  {
    $count = count($ids);
    $holders = $this->generatePlaceholders($count);
    
    $stm = $this->db->prepare("SELECT * FROM `{$this->tableName}` WHERE `{$this->primaryKey}` IN(" . implode(', ', $holders) . ")");
    $stm->execute(array_combine($holders, $ids));
    
    return $stm->fetchAll(PDO::FETCH_CLASS, $this->entity);
  }
}
<?php

/**
 *  Class Rex\Util\Hasher
 *
 *  Just a simple little something for dealing with hashes. There's a few settings at the top
 *  but you shouldn't really need to change those except for the global salt, which you should
 *  change for sure. 3000 iterations might sound like a lot but it shouldn't even take 1/10th
 *  of a second, yet it will slow rainbow tables a lot
 *
 *  Public methods:
 *
 *  setSaltLength
 *  setAlgorithm
 *  setIterations
 *
 *  hash
 *  check
 *
 *  Sample usage:
 *
 *  $hasher = new Hasher(array(
 *    'algorithm' => 'sha256'   <-- optional config stuff
 *  ));
 *  $hash = $hasher->hash('password');
 *  $match = $hasher->check('password', $hashFromDB);
 *
 */
 
namespace Rex\Util;
 
class HasherException extends \Exception {}

class Hasher
{
  // defaults just in case
  protected $saltLength = 8;
  protected $iterations = 3000;
  protected $algorithm = 'sha256';
  // a getter is used for this one so you can store it in the DB or in a file or something
  // this shouldn't be set manually, it should be worked in via extension
  protected $globalSalt = 'you-should-change-this';
  
  public function __construct($config = null)
  {
    if(!is_null($config)){
      $this->config($config);
    }
  }
  
  // set all things in one go
  public function config(array $config)
  {
    if(isset($config['salt-length'])){
      $this->setSaltLength($config['salt-length']);
    }
    if(isset($config['iterations'])){
      $this->setIterations($config['iterations']);
    }
    if(isset($config['algorithm'])){
      $this->setAlgorithm($config['algorithm']);
    }
  }
  // salt length must be integer and bigger than 0
  public function setSaltLength($length)
  {
    // convert to string for ctype check as potential strings won't pass in_int and casting to (int) 
    // could give misleading results
    $length = (string) $length;
    if(!ctype_digit($length)) throw new HasherException('Invalid number specified for hashing salt length - must be an integer');
		if($length > 32 || $length < 4) throw new HasherException('Hashing salt length must be greater than 3 and less than 33');
    $this->saltLength = (int) $length;
  }
  // this is in a function in case you need to extend and put the global salt in a 
  // separate file or something
  protected function getGlobalSalt()
  {
    return $this->globalSalt;
  }
  // this is to check the global salt has been changed!!
  final private function checkGlobalSalt()
  {
    if($this->getGlobalSalt() == 'j303mf03s{}d93&&l;xsd'){
      throw new HasherException('Global Salt has not been changed from the default!!');
    }
  }
  // iterations must integer and greater than 0
  public function setIterations($iter)
  {
    // convert to string for ctype check as potential strings won't pass in_int and casting to (int) 
    // could give misleading results
    $iter = (string) $iter;
    if(!ctype_digit($iter) || $iter == 0) throw new HasherException('Invalid number specified for hashing iterations');
    $this->iterations = $iter;
  }
  // will check if algo is available
  public function setAlgorithm($algo)
  {
    if(!in_array($algo, hash_algos())) throw new HasherException('Requested hash is not available');
    $this->algorithm = $algo;
  }
  // use to generate the private salt, will output hex characters only
  protected function privateSalt()
  {   
    return substr(md5(uniqid(rand(), TRUE)), 0, $this->saltLength);
  }
 	// main hashing function, hashes with global salt, then hashes that with private salt
  // iterated on a ton of times, leave the salt out for the first time
  public function hash($str, $salt = null)
  {
    // check the global salt has been changed
    $this->checkGlobalSalt();
    
    // if it's null, then it's the first time and we need a salt
    if(is_null($salt)) {
      $salt = $this->privateSalt();
    }
    for($i = 0; $i < $this->iterations; $i++) {
      $str = hash( $this->algorithm, hash( $this->algorithm, $str . $this->getGlobalSalt() ) . $salt );
    }
    return $salt . $str;
  }
  // comparison stuff, returns true or false
  public function check($str, $hash)
  {
    $salt = substr($hash, 0, $this->saltLength);
    return ($this->hash($str, $salt) == $hash);
  }
}
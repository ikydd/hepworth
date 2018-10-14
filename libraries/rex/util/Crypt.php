<?php

/*
 *	Class Rex\Util\Crypt
 *
 *	Will encryptify and secretify your stuff for you!!
 *
 *	Remember to set the location for your things in the lockers
 *	and to create a .key file (in your locker) if you want to use
 *
 *  It two modes: one saves the vector as a file and the other returns
 *  just the encrypted result.
 *
 *  Saving it as a file requires a path name (minus extension, it will
 *  automatically be given the .iv extension) in both cases - encrypting
 *  and decrypting - like "users/12". Not saving it as a file doesn't require you to keep
 *  the vector stored and so effectively doesn't really have one. "This is 
 *  nonsense" you say! Well, the thing is if you're keeping the vectors
 *  stored in the database like you will be with the actual encryption 
 *  results then you've got both the vector and the encryption in the same
 *  place and so if they get one they get the other which renders the whole point of
 *  using a vector pointless anyway.
 *
 *  Public methods:
 *
 *  Sample usage:
 *
 *  $crypt = new Crypt(array('mode' => MCRYPT_MODE_ECB, 'cipher' => MCRYPT_RIJNDAEL_256));
 *
 *  $encrypt = $crypt->encrypt('password', 'users/1');
 *  $decrypt = $crypt->decrypt($b, 'users/1');
 *
 */
 
namespace Rex\Util;

class CryptException extends \Exception {}
 
class Crypt
{
  // location of the storage for keys
  private $locker = 'vectors/';
  // current mode of encryption
  private $mode = MCRYPT_MODE_CBC;
  // name of file key if using that
  private $keyFile = 'vectors/.key';
  // super secret cipher
  private $cipher = MCRYPT_RIJNDAEL_256;
  
  public function __construct($config = null)
  {
    // check Mcrypt is turned on !!
    if(!function_exists('mcrypt_encrypt')) throw new CryptException('Mcrypt not enabled - Crypt Class unusable');
    
    if(!is_null($config)){
      $this->config($config);
    }
  }
  
  //set all things in one go
  public function config(array $config)
  {
    if(isset($config['locker'])){
      $this->setLocker($config['locker']);
    }
    if(isset($config['key-file'])){
      $this->setKeyFile($config['key-file']);
    }
    if(isset($config['cipher'])){
      $this->setCipher($config['cipher']);
    }
    if(isset($config['mode'])){
       $this->setMode($config['mode']);
    }
  }
  
  protected function isNotUsingVector()
  {
    return ($this->mode == MCRYPT_MODE_ECB);
  }
  
  public function setLocker($locker)
  {
    $this->locker = rtrim($locker, '/') . '/';
      // test the getLocker function
    if(!is_dir($this->getLocker())) throw new CryptException('Locker directory does not exist');
  }
	// location of files (including keyFile) -- function, so you can
  // get some relative paths or something (might need to call a framework function for example)
	protected function getLocker()
  {    
    return $this->locker;
	}
  
  public function setKeyFile($file)
  {
    $this->keyFile = $file;
    // test it's there
    if(!file_exists($this->getKeyFile())) throw new CryptException('Key file does not exist');
  }
  
	// location of files (including keyFile) -- function, so you can
  // get some relative paths or something (eg framework call)
  protected function getKeyFile()
  {
    return $this->keyFile;
  }
  // set cipher, will check if it exists
  public function setCipher($cipher)
  {
    if(!in_array($cipher, mcrypt_list_algorithms())) throw new CryptException('Requested cipher not supported');
    $this->cipher = $cipher;
  }
  // set mode, will check if it exists
  public function setMode($mode)
  {
    if(!in_array($mode, mcrypt_list_modes())) throw new CryptException('Requested mode not supported');
    $this->mode = $mode;
  }

	// global encrypt function
	public function encrypt($str, $path = null)
	{
    if(!$this->isNotUsingVector()){
      // generate a new vector
      $iv = $this->createVector($path);
    }
      
    // return encrypted and vector in array
    return mcrypt_encrypt($this->cipher, $this->key(), $str, $this->mode, $iv);
	}
	// global decrypt function
	public function decrypt($data, $path = null)
	{
    if(!$this->isNotUsingVector()){
      // get the previous vector
      $iv = $this->retrieveVector($path);
    }
    
    // decrypt
    return $this->trimmage(mcrypt_decrypt($this->cipher, $this->key(), $data, $this->mode, $iv));
	}
  // trim nulls off - for some reason it whacks a load of 'null's on the end (about 8 or so).....wtf?
  private function trimmage($crypt)
  {
    return rtrim($crypt, "\0");
  }
  // abstract the get vector size
  private function getVectorSize()
  {
    return mcrypt_get_iv_size($this->cipher, $this->mode);
  }
	// generate a new vector
	private function generateVector()
	{
		// get the size of vector, which depends on the cipher and mode
		$iv_size = $this->getVectorSize();
		// generate the iv randomly
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		
		return $iv;
	}
  // retrieves an existing vector
  private function retrieveVector($vectorID = null)
  {
    // chuck out if it's ECB as that doesn't need a vector
    if($this->isNotUsingVector()) throw new CryptException('You have supplied a vector, but the current encryption mode does not use one');
		// check you got a uid
		if(is_null($vectorID)) throw new CryptException('Vector path is missing for vector.');
    
    $iv = $this->loadVector($vectorID);
    
    if($this->getVectorSize() != strlen($iv)) throw new CryptException('Vector is wrong size, there must have been a mistake somewhere.');

    return $iv;
  }
  // this will load a file somewhere
  // extend and overwrite if you want to load from DB or something
  protected function loadVector($vectorID)
  {
    $path = $this->getLocker() . $vectorID . '.iv';
    
    // create the dir if necessary
    if(!file_exists($path)) throw new CryptException('Vector is missing');
    if(!is_readable($path)) throw new CryptException('Vector is not readable');
    
    return file_get_contents($path);
  }
	// creates a new vector for this user and saves
	private function createVector($vectorID = null)
	{
    // chuck out if it's ECB as that doesn't need a vector
    if($this->isNotUsingVector()) throw new CryptException('You have supplied a vector, but the current encryption mode does not use one');
		// check you got a uid
		if(is_null($vectorID)) throw new CryptException('Path is missing for vector.');
    
    //generate a vector
    $iv = $this->generateVector();
    // save it
    $this->saveVector($iv, $vectorID);
    
    return $iv;
	}
  
  // this will save as a file somewhere
  // extend and overwrite if you want to save to DB or something
  protected function saveVector($iv, $vectorID)
  {
	  // get the file path  
    $path = $this->getLocker() . $vectorID . '.iv';
    
    // make path if doesn't exist
    if(!file_exists(dirname($path))) {
      mkdir(dirname($path), 0777, true);
    }
    
    // tuck it away and return it
    file_put_contents($path, $iv);
  }
  
	// get the master key from outside the web root
  // extend and overwrite if you want to use something different
	protected function key()
	{
    // ... otherwise we use the file
		$path = $this->getKeyFile();
		
		// check it exists then take the right length from it
		if(!file_exists($path))	throw new CryptException('Crypt: Key is missing.');

		// get key size
		$size = mcrypt_get_key_size($this->cipher, $this->mode);
		
    return file_get_contents($path, NULL, NULL, 0, $size);
	}
}
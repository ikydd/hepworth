<?php

class Auth
{
	public static function isLoggedIn()
	{
    $user = self::getUser();
		if ($user && !is_null($user->id)) {
			return $user;
		}	/* else {
			return cookiemaster::check();
		} */
	}
  
  public static function storeUser(User $user)
  {
    Memo::store('user', $user);
  }
  
  public static function getUser()
  {
    return Memo::fetch('user');
  }
  
  public static function clearUser()
  {
    Memo::delete('user');
  }
	
	public function authenticate($type, User $user, $param)
	{
		$authenticator = $this->factory($type);
		return $authenticator->authenticate($user, $param);
	}
	
	protected static function factory($type)
	{
    $class = 'Auth' . ucfirst($type);
    if (class_exists($class)) {
      return new $class;
    }
    throw new Exception("Auth type {$type} does not exist.");
	}
}

?>
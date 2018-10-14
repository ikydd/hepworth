<?php

class AuthStandard implements AuthenticatorInterface
{
	public function authenticate(User $user, $match)
	{
		// check for stored password
		if (!$user->pass) {
      throw new AuthException('Unable to authenticate that user');
		}
    
    // check to see if they've been locked out
    if($user->lockout && strtotime($user->lockout) + 60*20 > time()){
      throw new AuthException('This account has been locked out for 20 minutes due to password failures');
    }
    
		// check hash against password
    $hash = new MyHasher();
		if(!$hash->check($match, $user->pass)){
      throw new AuthException('Invalid username or password');
    }
    
    return true;
	}
}

?>
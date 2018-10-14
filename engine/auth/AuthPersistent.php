<?php

class AuthPersistent implements AuthenticatorInterface
{
	public function authenticate(User $user, $match)
	{
		// check for stored token
		if (!$user->token) {
			throw new AuthException('Unable to login user');
		}
		// check for timeout
		if ($user->timeout < date('Y-m-d H:i:s')) {
			throw new AuthException('Session timed out');
		}
		// check for match
		if ($match != $user->token) {
			throw new AuthException('Invalid session');
		}
		return true;
	}
}

?>
<?php

interface AuthenticatorInterface
{
	public function authenticate(User $user, $match);
}
<?php

class LoginPassVisitor implements Visitor
{
	public function visit($obj)
	{
		// reset token
		$obj->token = md5(uniqid(rand(), TRUE));
    $obj->lockout = null;
    $obj->failcount = 0;
		// regen session on privilege elevation
		session_regenerate_id();
    
    $model = new UserModel();
    $model->save($obj);
	}
}
<?php

class NewUserVisitor implements Visitor
{
	public function visit($obj)
	{
		// reset token
    $obj->ident = substr(md5(uniqid(rand(), TRUE)), 0, 8);
		$obj->token = md5(uniqid(rand(), TRUE));
    $obj->lockout = null;
    $obj->failcount = 0;
    
    $model = new UserModel();
    $model->save($obj);
	}
}
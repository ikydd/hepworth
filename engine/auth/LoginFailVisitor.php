<?php
// Visitor: updates login fail values and check for lockout

class LoginFailVisitor implements Visitor
{
	public function visit($obj)
	{
		// check if last fail was more than twenty minutes ago
		// reset fail count if it was
		if ((strtotime($obj->last_fail) + 60*20) < time()) {
			$obj->failcount = 0;
		}
		$obj->failcount++;
		$obj->last_fail = date('Y-m-d H:i:s');
		// check failcount + time and lockout if necessary
		if ($obj->failcount > 3) { // abstract me please
			// set lockout time and cancel failcount for when lockout expires
			$obj->lockout = date('Y-m-d H:i:s');
			$obj->failcount = 0;
		}
    
    $model = new UserModel;
    $model->save($obj);
	}
}

?>
<?php
// Visitor: grabs cookie data, validates it, sets timeout and
// Delivers cookies

class RememberMeVisitor implements Visitor
{
	public function visit($obj)
	{
		// validate cookie info
		$clean['ident'] = ctype_alnum($obj->ident) ? $obj->ident : '';
		$clean['token'] = ctype_alnum($obj->token) ? $obj->token : '';
		
		// set timeout and save
		$obj->timeout = date('Y-m-d H:i:s', strtotime('+90 days'));
    
    $model = new UserModel();
		$model->save($obj);
		
		// escape data for output (bit redundant here, but fuck it)
		$cookie['ident'] = htmlentities($clean['ident'], ENT_QUOTES, 'UTF-8');
		$cookie['token'] = htmlentities($clean['token'], ENT_QUOTES, 'UTF-8');
		
		// set cookies
		setcookie('ident', $cookie['ident'], strtotime($obj->timeout), '/');
		setcookie('token', $cookie['token'], strtotime($obj->timeout), '/');
	}
}

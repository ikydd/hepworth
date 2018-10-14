<?php

class Memo
{
  // stores variable in SESSION
	public static function store($name, $value)
	{
    $_SESSION[$name] = $value;
	}
	// stores variable in SESSION array
	public static function stack($name, $value, $key = null)
	{
    // check it's an array, init if not
    if (!isset($_SESSION[$name]) || !is_array($_SESSION[$name])) {
      $_SESSION[$name] = array();
    }
    if($key) $_SESSION[$name][$key] = $value;
    else $_SESSION[$name][] = $value;
	}
	// retrieves variable from SESSION without removing
	public static function fetch($name)
	{
    // check it's set before going for the offset (to prevent notice)
    return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
	}
  // retrieves a variable and removes it too
  public static function remove($name)
  {
    $value = self::fetch($name);
    self::delete($name);
    return $value;
  }
  // manually remove a variable
  public static function delete($name)
  {
    unset($_SESSION[$name]);
  }
  // remove all current session data
  public static function flash()
  {
    session_unset();
    $_SESSION = array();
  }
}
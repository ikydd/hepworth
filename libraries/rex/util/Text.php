<?php

/*
 *	Class Text
 *
 *	The amount of times I need to do some of this shizz, and I only now have finally
 *	got round to doing a class for it. I'm useless.
 *
 */

namespace Rex\Util;

class Text
{
  public function __construct($string)
  {
    $this->str = $string;
  }
 
  // ucname
  // word limit
  // auto typography (paragraphs, breaks)
  
  // strip out the tabs and new lines and stuff
  public function stripLines($str)
  {
    return preg_replace(array('/\t+/', '/\n+/', '/\r+/'), array('', '', ''), $str);
  }
  // some things will have breaks on the end in inappropriate places, which this should remove
  // (it only removes them from the beginning and the end)
  public function stripBreaks($str)
  {
    return preg_replace(array('/^<br( \/)?>/', '/<br( \/)?>$/'), array('', ''), $str);
  }
  // check if it's made up only of whitespace and/or breaks
  public function isWhitespace($str)
  {
    return !preg_match('/^[\s]*((<br( \/)?>)[\s]*)*$/', $str);
  }
  
  // chop off at a certain length
	public static function truncate($string, $length = 30, $ellipsis = '&#8230;')
	{
		// see if it's longer than length, if so chop it off at the length, but one shorter for the ellipsis
		if(strlen($string) > $length) $string = substr($string, 0, $length - 1) . $ellipsis;
		
		return $string;
	}
	
  // chop at a certain max length, but at the first separator (eg retaining whole words)
	public static function shorten($string, $length = 30, $separator = ' ', $ellipsis = '&#8230;')
	{
		// if string isn't long enough just return
		if(strlen($string) < $length) return $string;
		
		// if string contains none of the separators then truncate
		if(strpos($string, $separator) === false) return self::truncate($string);
		
		// chop it
		$string = substr($string, 0, $length);
		
		// find position of last separator
		$last = strrpos($string, $separator);
		
		// chop it again and add the ellipsis
		return substr($string, 0, $last) . $ellipsis;
	}
  
  // removes a load of potential separators and strips out non-alpha-nums (trims also)
  public static function toWords($string, $separator = ' ')
  {
    return preg_replace(array("/[^a-z0-9\-_\. ]/i", "/[\-_\. ]/"), array('', $separator), trim($string));
  }
  
  // convert string to camel caps
  public static function camelCaps($string, $first = false)
  {
    $seps = array('-','_','.');
    
    // replace all above with spaces
    $string = str_replace($seps, array(' ',' ',' '), $string);
    
    // capitalize
    $string = ucwords($string);
    
    // glue together
    $string = str_replace(' ', '', $string);
    
    // lower case first if needed
    if(!$first) $string = strtolower(substr($string, 0, 1)) . substr($string, 1);
    
    return $string;
  }
  
  // break into words separated by underscore
  public static function underscore($string)
  {
    return strtolower( self::toWords($string, '_') );
  }
  
  // break into words separated by a dot
  public static function dotted($string)
  {
    return strtolower( self::toWords($string, '.') );
  }
}
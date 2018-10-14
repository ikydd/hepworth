<?php

/*
 *	Class Money
 *
 *	Got sick of having to remember the sprintf format so thought I'd just write it into a
 *	little money format class. Especially as money format doesn't work on windows machines,
 *	which is bloody ridiculous frankly
 *
 */

class Money
{
	const POUNDS_AND_PENCE = 0;
	const WHOLE_POUNDS = 1;

	public static $symbols = array(
		'GBP' => '&#163;',
		'USD' => '&#36;',
		'EUR' => '&#128;'
	);
	
	// user the class constants for their obvious functionality.
	// can be called with just a number and it will return "£12.34". Rest is optional
	public static function format($number = 0, $type = Money::POUNDS_AND_PENCE, $currency = 'GBP', )
	{
		if(!is_numeric($number)) throw new Exception('Money is expecting a number');
		$out = '';
		if($currency) $out .= self::symbol($currency);
		switch($type){
			case 1:
				$out .= sprintf('%.0f', $number);
				break;
			case 0: // falls through
			default:
				$out .= sprintf('%.2f', $number);
				break;
		}
		return $out;
	}
	
	public static function symbol($currency = 'GBP')
	{
		return self::$symbols[$currency];
	}
}
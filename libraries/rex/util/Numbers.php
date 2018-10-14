<?php

namespace Rex\Util;

class Numbers
{
  public static function ordinal($number)
  {
    // check for 11th, 12th, 13th (and 100th, 111th, 112th etc)
    $excess = $n % 100;
    if (($excess > 10 && $excess < 14) || $number == 0){
      return "{$number}th";
    }
    // all the rest handled normally
    switch(substr($number, -1)) {
      case '1':    return "{$number}st";
      case '2':    return "{$number}nd";
      case '3':    return "{$number}rd";
      default:     return "{$number}th";
    }
  }
  
  public static function mean(/* variable */)
  {
    if (func_num_args() == 1 && is_array(func_get_arg(0))) {
      $list = func_get_arg(0);
    } else {
      $list = func_get_args();
    }
    // needs total of all numbers and number of numbers
    $total = 0;
    $count = 0;
    foreach($list as $number){
      $total = $total + $number;
      $count++;
    }
    return $total / $count;
  }
  
  public static function mode(/* variable */)
  {
    if (func_num_args() == 1 && is_array(func_get_arg(0))) {
      $list = func_get_arg(0);
    } else {
      $list = func_get_args();
    }
    // sort array into groups by value
    $counts = array_count_values($list);
    // iterate over each, compare to last, add to max if equal in count
    // break if not, this is because there can be multiple modes, so we need
    // to check
    $max = max($counts);
    $modes = array();
    foreach($counts as $mode => $count){
    if ($count == $max) {
        $modes[] = $mode;
      } else {
        break;
      }
    }
    // modes will return an array
    return $modes;
  }
  
  public static function median(/* variable */)
  {
    if (func_num_args() == 1 && is_array(func_get_arg(0))) {
      $list = func_get_arg(0);
    } else {
      $list = func_get_args();
    }
    // get length of list
    $length = count($list);
    $mid = $length / 2;
    // if uneven, then we can use the floor of the mid
    if($length % 2) {
      return $list[floor($mid)];
    } else {
      // if not then we have to add and average two together
      return ($list[$mid - 1] + $list[$mid]) / 2;
    }
  }
  
  // you can of course use "round" of course, but then 3.100 will become 3.1
  // so this is for fixing the amount of places after it
  public static function decimals($number, $points = 2)
  {
    return sprintf("%.{$points}f", $number);
  }
  
  public static function thousands($number, $decimals = null)
  {
    if(is_null($decimals)){
      // we have to find out how many decimals there are as number format
      // will chop any off that we don't specify
      $decimals = strlen(strrchr((string) $number, '.')) - 1;
    }
    return number_format($number, $decimals ?: 0, '.', ',');
  }
  
  // bit of a gimme this one!!
  public static function percent($number, $percent)
  {
    return $number * $percent / 100;
  }
}
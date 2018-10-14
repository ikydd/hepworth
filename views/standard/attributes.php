<?php 
$atts = array();
foreach($attributes as $name => $value){
  $atts[] =  $name . '="' . $value . '"';
}
$atts = implode(' ', $atts);
if($atts) $atts = ' ' . $atts . ' ';
echo $atts;
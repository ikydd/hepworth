<?php

class ImageException extends Exception {}

class Image
{
  protected $compression = 0;
  protected $quality = 100; 
  
 	public function __construct($image)
	{
    ini_set('memory_limit', '256M');
    
    // check some stuff
    if(!file_exists($image)) throw new ImageException('File cannot be found');
    if(filesize($image) > pow(2,23)) throw new ImageException('File is over 8Mb and so is probably too big for Image class');
		if(pathinfo($image, PATHINFO_EXTENSION) == 'bmp') throw new ImageException('GD does not support bmp files');
    
    $size = getimagesize($image);
		$this->type = $size['mime'];
		
    // set up the GD image
		switch($this->type){
			case 'image/jpeg':
				$this->image = @imagecreatefromjpeg($image);
				break;
			case 'image/png':
				$this->image = @imagecreatefrompng($image);
				break;
			case 'image/gif':
				$this->image = @imagecreatefromgif($image);
				break;
 
			default:
        throw new ImageException('Invalid file type');
				break;
		}
    // if it didn't set up then throw an error
    if(!$this->image) throw new ImageException('Could not open file');
		
		$this->setDimensions();
	}
	
	protected function setDimensions()
	{
		$this->width = imagesx($this->image);
		$this->height = imagesy($this->image);
    $this->position = array('x' => 0, 'y' => 0);
	}
  
	protected function resize()
	{
		// our new blank image of the size we want
    $resizedImage = imagecreatetruecolor($this->newdimensions['width'], $this->newdimensions['height']);

    imagecopyresampled(
      $resizedImage,        # the resized image we want to create
      $this->image,         # the image we're going to resize
      0,                    # the starting co-ords of new image (top left)
      0,
      $this->position['x'], # the starting co-ords of old image
      $this->position['y'],
      $this->newdimensions['width'],  # the width of the new image
      $this->newdimensions['height'], # the height of the new image
      $this->olddimensions['width'],  # the width of the old image
      $this->olddimensions['height']  # the height of the old image
    );

    // put it back into the var so we can continue to work with it
    $this->image = $resizedImage;
    $this->setDimensions();

    unset($this->newdimensions);
    unset($this->olddimensions);
    
    return $this;
	}
  
  // change the format
  public function reformat($format)
  {
    switch($format){
      case 'png':
        $this->type = 'image/png';
        break;
      case 'jpg':
        $this->type = 'image/jpeg';
        break;
      case 'gif':
        $this->type = 'image/gif';
        break;
      default:
        throw new ImageException('Invalid format requested');
        break;
    }
    return $this;
  }
	
  // rescales it retaining proportions with two options: min, max
	public function scale($width, $height, $type = 'max')
	{
		switch($type){
			
			// this will make it so the dimensions are the minimum size
			// for the new image
			case 'min':
				
        // get size ratio of width
				$ratio = $width / $this->width;
        // see if height will be below min height using width ratio
				if( $this->height * $ratio < $height ){
          // if it is, then we'll have to use the ratio against the height
					$ratio = $height / $this->height;
          // and set width
					$width = $this->width * $ratio;
				} else{
          // but if width ratio is good then set height
					$height = $this->height * $ratio;
				}
				
				$this->olddimensions = array('width' => $this->width, 'height' => $this->height);
				$this->newdimensions = array('width' => $width, 'height' => $height);
				
				break;
			
			// this will mean the dimensions are the max size
			// and so are a bounding box of sorts
			case 'max':
        // get width ratio
				$ratio = $width / $this->width;
        // if height will be above max height using width ratio
				if( $this->height * $ratio > $height ){
          // if it is, then we'll have to use the ratio from the height instead
					$ratio = $height / $this->height;
           // and set width
					$width = $this->width * $ratio;
				} else{
          // if width ratio is good then set the height
					$height = $this->height * $ratio;
				}
				
				$this->olddimensions = array('width' => $this->width, 'height' => $this->height);
				$this->newdimensions = array('width' => $width, 'height' => $height);
				
				break;
      default:
        throw new ImageException('Invalid scaling request');
        break;
		}
		
		return $this->resize();
	}
	
  // this will crop from one of five places, defaulting to centre - no resizing takes place
  // five options: centre, topleft, topright, bottomleft, bottomright
	public function crop($width, $height, $position = 'centre')
	{
    // height and width will be the same
		$this->olddimensions = array('width' => $width, 'height' => $height);
		$this->newdimensions = array('width' => $width, 'height' => $height);
    
    switch ($position){
      case 'topleft':
        $this->position = array('x' => 0, 'y' => 0);
        break;
      case 'topright':
        $this->position = array('x' => $this->width - $width, 'y' => 0);
        break;
      case 'bottomleft':
        $this->position = array('x' => 0, 'y' => $this->height - $height);
        break;
      case 'bottomright':
        $this->position = array('x' => $this->width - $width, 'y' => $this->height - $height);
        break;
      case 'centre':
        $this->position = array('x' => ($this->width / 2) - ($width / 2), 'y' => ($this->height / 2) - ($height / 2));
        break;
      default: 
        throw new ImageException('Invalid crop type');
        break;
    }
		
		return $this->resize();
	}
	
  // resizes but ignores proportional contraints
	public function distort($width, $height)
	{
		$this->olddimensions = array('width' => $this->width, 'height' => $this->height);
		$this->newdimensions = array('width' => $width, 'height' => $height);
		
		return $this->resize();
	}
  
  // turn it into greyscale/black&white
  public function desaturate()
  {
    imagefilter($this->image, IMG_FILTER_GRAYSCALE);
    
    return $this;
  }
  
  // specify the exact saturation 
  public function saturation($saturation = 0)
  {    
    // over 100 will cause artifacts and such
    if($saturation > 100) $saturation = 100;
    
    imagecopymergegray(
      $this->image,        # the starting image
      $this->image,        # the end image (the same on in this case)
      0,                   # the starting co-ords of new image (top left)
      0,
      0,
      0,
      $this->width,  # the width of the new image
      $this->height, # the height of the new image
      $saturation    # the level of saturation
    );
    
    return $this;
  }
  
  // contrast filter with a little input tweaking
  public function contrast($contrast = 0)
  {
    // contrast cycles round the bottom, to to avoid confusion just limit it to a range
    if($contrast < -100) $contrast = -100;
    
    // 100 contrast is flat grey, which seems weird, so reverse numbers
    imagefilter($this->image, IMG_FILTER_CONTRAST, $contrast*-1);
    
    return $this;
  }
  
  // might as well sort this out too as there's an easy filter for it
  public function brightness($brightness = 0)
  {
    imagefilter($this->image, IMG_FILTER_BRIGHTNESS, $brightness);
    
    return $this;
  }
  
  // there's no option to set the strength of blur, so just going to do 
  // repeated operations of it. Warning: THIS CAN GET REALLY SLOW IF YOU DO TOO MANY
  public function blur($times = 0)
  {
    // multiply by 3 so it's easier to  notice
    for($i = 0; $i < $times*3; $i++) imagefilter($this->image, IMG_FILTER_GAUSSIAN_BLUR);
    
    return $this;
  }
  // convert/analyse input to form proper RGB colours
  protected function rgb($colour)
  {
    $args = count($colour);
    // if HEX
    if($args == 1){
      // get the hex
      $hex = $colour[0];
      // trim hash off if present
      $hex = ltrim($hex, '#');
      // split into pairs or single as appropriate and convert
      if(strlen($hex) == 6){
        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));
      // if singles then we need to double them up before converting
      } else if(strlen($hex) == 3) {
        $red = substr($hex, 0, 1);
        $red = hexdec($red . $red);
        $green = substr($hex, 1, 1);
        $green = hexdec($green . $green);
        $blue = substr($hex, 2, 1);
        $blue = hexdec($blue . $blue);
      // anything other than 3 or 6 then just jib it off
      } else {
        throw new ImageException('Invalid hex - hexes must have 3 or 6 characters');
      }
    // if RGB
    } else if($args == 3) {
      $red = $colour[0];
      $green = $colour[1];
      $blue = $colour[2];
    // if no arguments then hell just bugger it
    } else if($args == 0) {
      return $this;
    } else {
      throw new ImageException('Invalid hue request - hue must have one or three arguments');
    }
    
    return array('red' => $red, 'green' => $green, 'blue' => $blue);
  }
  
  // change the hue
  public function hue(/* POLYMORPHIC: $hex || $red, $green, $blue */)
  {
    $colours = $this->rgb(func_get_args());
    
    imagefilter($this->image, IMG_FILTER_COLORIZE, $colours['red'], $colours['green'], $colours['blue'], 50);
    
    return $this;
  }
  // cheesy old colourise mania
  public function colourise(/* POLYMORPHIC: $hex || $red, $green, $blue */)
  {
    // need to check the args to avoid PHP notices
    $args = func_num_args();
    if($args == 1)  return $this->desaturate()->hue(func_get_arg(0));
    else if($args == 3)  return $this->desaturate()->hue(func_get_arg(0), func_get_arg(1), func_get_arg(2));
    else throw new ImageException('Invalid number of arguments for colourise');
  }
  // the classic cheesy invery nonsense. There's a filter for it, so why not?
  public function invert()
  {
    imagefilter($this->image, IMG_FILTER_NEGATE);
    
    return $this;
  }
  // set compression (png) and quality (jpg) level - options: none, low, med, high, max
  public function compress($compression = 'med')
  {
    switch($compression){
      case 'none':
        $this->compression = 0;
        $this->quality = 100;
        break;
      case 'low':
        $this->compression = 3;
        $this->quality = 80;
        break;
      case 'med':
        $this->compression = 5;
        $this->quality = 60;
        break;
      case 'high':
        $this->compression = 7;
        $this->quality = 20;
        break;
      case 'max':
        $this->compression = 9;
        $this->quality = 0;
        break;
      default:
        throw new ImageException('Unknown compression level');
        break;
    }
    
    return $this;
  }
  
  // check whether it has the right extension or not
  protected function extension($path, $exts)
  {
    if(!is_array($exts)){
      $exts = $exts ? array($exts) : array();
    }
    $match = false;
    foreach($exts as $ext){
      $ext = '.' . ltrim($ext, '.'); // doublcheck it has the dot
      if(substr_compare($path, $ext, -1 * strlen($ext), strlen($ext), true) == 0){
        $match = true;
        break;
      }
    }
    return $match;
  }

  // save the finished file
	public function save($path)
	{
    // make the folder if needed
		if(!file_exists(dirname($path))) mkdir(dirname($path), 0777, true);
		switch($this->type){
			case 'image/jpeg':
        $path .= $this->extension($path, array('.jpg', '.jpeg')) ? '' : '.jpg';
				imagejpeg($this->image, $path, $this->quality);
				break;
			case 'image/png':
        $ext = '.png';
        $path .= $this->extension($path, $ext) ? '' : $ext;
				imagepng($this->image, $path, $this->compression);
				break;
			case 'image/gif':
        $ext = '.gif';
        $path .= $this->extension($path, $ext) ? '' : $ext;
				imagegif($this->image, $path);
				break;
			default:
        throw new ImageException('File type invalid, file cannot be saved');
				break;
		}
    
    return $this;
	}
  
  // output directly to browser, but can only output one at a time of course
  public function output()
  {

    // kill it all if headers done already
    if(headers_sent()) throw new ImageException('Headers already sent');
    
		switch($this->type){
			case 'image/jpeg':
        header('Content-type: image/jpeg');
        imagejpeg($this->image, null, $this->quality);
				break;
			case 'image/png':
        header('Content-type: image/png');
				imagepng($this->image, null, $this->compression);
				break;
			case 'image/gif':
        header('Content-type: image/gif');
				imagegif($this->image);
				break;
			default:
        throw new ImageException('File type invalid, file cannot be saved');
				break;
		}
    exit;
  }
	
  // free up the memory when not needed anymore
	public function __destruct()
	{
		imagedestroy($this->image);
	}
}
<?php

/**
 *  Class Rex\Util\ListReader
 *
 *  A useful little class for reading and writing text files with line-by-line data
 *  What's the point of doing that? Well, sometimes less technically-minded users need
 *  to get data into the system (eg a list of email address) and you can't be bothered to
 *  write some interface for it because it's not worth it, and more particular syntax
 *  as in an .ini file or something will just be a pain. Everyone, however, can handle
 *  pressing return a writing another entry.
 *
 *  Also, not everything needs to be kept in sodding databases absolutely all the time.
 *
 *  The file will both blank lines and trim whitespace off the ends, so the final contents
 *  array should be all hunky-dory.
 *
 *  Originally just a read with no write capability, figured I might as well give it write
 *  to just for the heck of it.
 *
 */
 
namespace Rex\Util;
 
class ListReaderException extends \Exception {}

class ListReader
{
	private $file;
	private $handle;
	
	public function __construct($file = null)
	{
		// set file for object, new object required if different file to be opened
		$this->file = $file;
	}
  // open handle in chosen mode
	private function open($op = 'r+t')
	{
		if (!$this->handle = @fopen($this->file, $op)) {
      throw new ListReaderException('File could not be found or read.');
    }
    
    return $this;
	}
  // close handle
	private function close()
	{
		fclose($this->handle);
    
    return $this;
	}
  // add to end of file
  public function add($content)
  {
    // open readable and writable, pointer at the end
    $this->open('r+t');
    // actually do it
    $this->write($content);
  }
  // write, as in replace the whole file, or write from scratch
  public function replace($content)
  {
    // open readable and writable, auto-truncate file
    $this->open('w+t');
    // actually do it
    $this->write($content);
  }
  // the actual writing bit
  private function write($content)
  {
    // convert non-array to array
    if(!is_array($content)) {
      $content = array($content);
    } 
    $this->insert($content);
    return $this->close();
  }
  // write the lines to the text file
  private function insert(array $lines)
  {
    // if file is empty then we don't need a new line to start with, otherwise we will
    $text = '';
    // just reading the first 100 bytes to see if there's anything there
    if(strlen(fread($this->handle, 100)) != 0) {
      $text .= "\n";
    }
    $text .= implode("\n", $lines);
    
    // write to file, throw exception if it goes wrong
    if(fwrite($this->handle, $text) === false) {
      throw new ListReaderException('Could not write to file');
    }

    return $this;
  }
  // return the contents of the file in an array
	public function contents()
	{
    // open in readable only
		$this->open('rt');
		$contents = array();
		
    // set to beginning just in case
    fseek($this->handle, 0);
    // for each line until end, add it to array
    while(!feof($this->handle)){
      $line = trim(fgets($this->handle));
      // line is trimmed, and skipped if empty
      if (!empty($line)) {
        $contents[] = $line;
      }
    }
    $this->close();
		
		return $contents;
	}
}

?>
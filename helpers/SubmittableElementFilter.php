<?php

class SubmittableElementFilter extends FilterIterator
{
  public function accept()
  {
    return ($this->key() == 'name' && strlen($this->current()) > 0);
  }
}
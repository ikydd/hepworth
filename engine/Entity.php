<?php

class Entity
{
  public function accept(Visitor $visitor)
  {
    $visitor->visit($this);
  }
}
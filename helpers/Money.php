<?php

class Money
{
  public function format($amount)
  {
    return sprintf('%0.2f', $amount);
  }
}
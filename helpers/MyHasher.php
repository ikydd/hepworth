<?php

class MyHasher extends Hasher
{
  protected function getGlobalSalt()
  {
    return file_get_contents(__DIR__ . '/../configs/.salt');
  }
}
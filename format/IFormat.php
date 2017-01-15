<?php

namespace format;

interface IFormat
{
  public function save($data, $file);
}
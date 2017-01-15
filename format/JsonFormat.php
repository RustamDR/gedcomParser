<?php

namespace format;

class JsonFormat implements IFormat
{

  public function save($data, $file)
  {
    file_put_contents($file, json_encode($data));
  }

}
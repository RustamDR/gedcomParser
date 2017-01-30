<?php

namespace format\aclasses;

/**
 * Interface IFormat
 * @package format\aclasses
 */
interface IFormat
{
  
  /**
   * Сохранение в файл
   * @param $file
   * @return mixed
   */
  public function save($file);

}
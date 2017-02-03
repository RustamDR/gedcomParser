<?php

namespace format\interfaces;

/**
 * Interface IFormat
 * @package format\interfaces
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
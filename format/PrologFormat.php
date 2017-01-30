<?php

namespace format;

use format\aclasses\AFormat;

/**
 * Сохранение в формат Prolog
 * Class PrologFormat
 * @package format
 */
class PrologFormat extends AFormat
{

  /**
   * @inheritdoc
   */
  public function save($file)
  {
    foreach ($this->_indies as $item) {
      $item->name = $this->atom($item->name);
      $string = ($item->sex === 'F' ? 'female' : 'male') . "({$item->name})." . PHP_EOL;
      file_put_contents($file, $string, FILE_APPEND);
    }

    foreach ($this->_fams as $item) {
      $father = $item->husb ? $item->husb->name : 'unknown';
      $mother = $item->wife ? $item->wife->name : 'unknown';

      foreach ($item->chil as $child) {
        $child = $child->name;
        $string = "parents({$child},{$father},{$mother})." . PHP_EOL;
        file_put_contents($file, $string, FILE_APPEND);
      }
    }
  }

  /**
   * Очистка значений атомов
   * @param $value
   * @return mixed
   */
  protected function atom($value)
  {
    $value = preg_replace('/[^A-Za-z0-9]+/i', '_', lcfirst(trim($value, '/')));

    return $value;
  }

}
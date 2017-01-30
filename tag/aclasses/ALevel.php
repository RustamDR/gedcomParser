<?php

namespace tag\aclasses;

/**
 * Общий класс характеризующий все теги
 * Class ALevel
 * @package tag\aclasses
 */
abstract class ALevel
{

  /**
   * Уровень вложенности
   * @var integer
   */
  public $level;

  /**
   * ALevel constructor.
   * @param $level
   */
  protected function __construct($level)
  {
    $this->level = $level;
  }

  /**
   * Ссылочные правила тэгов
   * @return mixed
   */
  abstract public function referenceRules();

}
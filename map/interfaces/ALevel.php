<?php

namespace map\interfaces;

/**
 * Общий класс характеризующий все теги
 * Class ALevel
 * @package map\interfaces
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
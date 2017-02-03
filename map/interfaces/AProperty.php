<?php

namespace map\interfaces;

/**
 * Свойства в виде объектов
 * Class AProperty
 * @package map\interfaces
 */
abstract class AProperty extends ALevel
{

  /**
   * Фабричный метод создания сущности свойства
   * @param integer $level
   * @return AProperty
   */
  final public static function getObject($level = 0)
  {
    return new static($level);
  }

}
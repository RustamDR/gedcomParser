<?php

namespace tag\aclasses;

/**
 * Свойства в виде объектов
 * Class AProperty
 * @package tag\aclasses
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
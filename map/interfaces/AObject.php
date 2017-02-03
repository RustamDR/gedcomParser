<?php

namespace map\interfaces;

/**
 * Абстрактный мультитон для объектов тегов (каждый класс объектов тега хранит коллекцию созданных сущностей)
 * Class AObject
 * @package map\interfaces
 */
abstract class AObject extends ALevel
{

  /**
   * @var integer
   */
  public $id;

  /**
   * Коллекция инициированных сущностей  в классе
   * @var array
   */
  public static $initializedObjects = [];

  /**
   * AObject constructor.
   * @param integer $id
   */
  final protected function __construct($id)
  {
    $this->id = $id;
    parent::__construct(0);
  }

  /**
   * Сохранение всех созданных сущностей, доступ по его Id
   * @param integer $id
   * @param integer $level
   * @return AObject
   */
  final public static function getObject($id, $level = 0)
  {
    if (!isset(static::$initializedObjects[$id])) {
      static::$initializedObjects[$id] = new static($id, $level);
    }

    return static::$initializedObjects[$id];
  }

  /**
   * @inheritdoc
   */
  final protected function __clone()
  {
  }

}
<?php

namespace format\interfaces;
use map\objects\Fam;
use map\objects\Indi;

/**
 * Абстрактный класс содержащий данные для сохранения
 * Class AFormat
 * @package format\interfaces
 */
abstract class AFormat implements IFormat
{

  /**
   * @var Indi[]
   */
  protected $_indies;

  /**
   * @var Fam[]
   */
  protected $_fams;

  /**
   * Конструктор для стратегий сохранения
   * AFormat constructor.
   */
  final public function __construct()
  {
    $this->_indies = Indi::$initializedObjects;
    $this->_fams = Fam::$initializedObjects;
  }
  
}
<?php

namespace format\aclasses;

use tag\Fam;
use tag\Indi;

/**
 * Абстрактный класс содержащий данные для сохранения
 * Class AFormat
 * @package format\aclasses
 */
abstract class AFormat implements IFormat
{

  /**
   * @var \tag\Indi[]
   */
  protected $_indies;

  /**
   * @var \tag\Fam[]
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
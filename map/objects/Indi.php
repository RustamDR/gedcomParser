<?php

namespace map\objects;

use map\interfaces\AObject;
use map\properties\Birt;

/**
 * Класс описывающий тэг INDI
 * Class Indi
 * @package map\objects
 */
class Indi extends AObject
{

  /**
   * @var string
   */
  public $name;

  /**
   * @var string
   */
  public $sex;

  /**
   * @var Birt
   */
  public $birt;

  /**
   * @var Indi[]
   */
  public static $initializedObjects = [];

  /**
   * @inheritdoc
   */
  public function referenceRules()
  {
    return [
        'birt' => 'birt',
    ];
  }

}
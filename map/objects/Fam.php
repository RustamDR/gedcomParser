<?php

namespace map\objects;

use map\interfaces\AObject;

/**
 * Класс, описывающий тэг FAM
 * Class Fam
 * @package map\objects
 */
class Fam extends AObject
{

  /**
   * @var Indi
   */
  public $husb;

  /**
   * @var Indi
   */
  public $wife;

  /**
   * @var Indi[]
   */
  public $chil = [];

  /**
   * @var Fam[]
   */
  public static $initializedObjects = [];

  /**
   * @inheritdoc
   */
  public function referenceRules()
  {
    return [
        'husb' => 'indi',
        'wife' => 'indi',
        'chil' => 'indi',
    ];
  }

}
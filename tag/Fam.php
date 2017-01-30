<?php

namespace tag;

use tag\aclasses\AObject;

/**
 * Класс, описывающий тэг FAM
 * Class Fam
 * @package tag
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
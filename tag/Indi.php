<?php

namespace tag;

use tag\aclasses\AObject;

/**
 * Класс описывающий тэг INDI
 * Class Indi
 * @package tag
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
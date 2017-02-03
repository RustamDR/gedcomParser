<?php

namespace map\properties;

use map\interfaces\AProperty;

/**
 * Класс, описывающий тэг BIRT
 * Class Birt
 * @package map\properties
 */
class Birt extends AProperty
{

  /**
   * Дата рождения
   * @var string
   */
  public $date;

  /**
   * Место рождения
   * @var string
   */
  public $plac;

  /**
   * @var Sour
   */
  public $sour;

  /**
   * @inheritdoc
   */
  public function referenceRules()
  {
    return [
      'sour' => 'sour',
    ];
  }

}
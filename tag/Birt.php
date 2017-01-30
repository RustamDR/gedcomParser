<?php

namespace tag;

use tag\aclasses\AProperty;

/**
 * Класс, описывающий тэг BIRT
 * Class Birt
 * @package tag
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
   * @inheritdoc
   */
  public function referenceRules()
  {
    return [];
  }

}
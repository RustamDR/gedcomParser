<?php

namespace lib;

/**
 * Структура gedcom для парсинга
 * Class GedcomTags
 * @package lib
 */
class GedcomTags
{
  protected function indiParse($id)
  {
  }

  protected function famParse($id)
  {
  }

  protected function sexParse()
  {
  }

  protected function nameParse()
  {
  }

  protected function husbParse()
  {
  }

  protected function wifeParse()
  {
  }

  protected function chilParse()
  {
  }

  /**
   * Отношения тэгов
   * @return array
   */
  public static function referenceRules()
  {
    return [
        'husb' => 'indi',
        'wife' => 'indi',
        'chil' => 'indi',
    ];
  }

}
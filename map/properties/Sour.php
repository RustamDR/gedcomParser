<?php
namespace map\properties;

use map\interfaces\AProperty;

class Sour extends AProperty
{
  public $sour;
  public $page;

  public function referenceRules()
  {
    return [
        'sour' => 'sour',
    ];
  }
}
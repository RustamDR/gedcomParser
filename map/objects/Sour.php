<?php
namespace map\objects;

use map\interfaces\AObject;

/**
 * Class Sour
 * @package map\objects
 */
class Sour extends AObject
{

  public $auth;
  public $titl;
  public $publ;

  /**
   * @var Sour[]
   */
  public static $initializedObjects = [];


  public function referenceRules()
  {
    return [];
  }

}
<?php

namespace format;

use lib\Family;
use lib\Person;

class PrologFormat implements IFormat
{

  /**
   * @param $data
   * @param $file
   */
  public function save($data, $file)
  {
    if (isset($data['indi'])) {
      /** @var Person $item */
      foreach ($data['indi'] as $item) {
        $item->name = $this->atom($item->name);
        $string = ($item->sex === 'F' ? 'female' : 'male') . "({$item->name})." . PHP_EOL;
        file_put_contents($file, $string, FILE_APPEND);
      }
    }

    if (isset($data['fam'])) {
      /** @var Family $item */
      foreach ($data['fam'] as $item) {
        $father = 'unknown';
        $mother = 'unknown';

        /** @var Person $person */
        if ($item->husb) {
          $person = array_shift($item->husb);
          $father = $person ? $person->name : $father;
        }

        if ($item->wife) {
          $person = array_shift($item->wife);
          $mother = $person ? $person->name : $mother;
        }

        foreach ($item->chil as $child) {
          $child = $child->name;
          $string = "parents({$child},{$father},{$mother})." . PHP_EOL;
          file_put_contents($file, $string, FILE_APPEND);
        }
      }
    }
  }

  protected function atom($value)
  {
    $value = preg_replace('/_$/', '', preg_replace('/[ \/.,<>#@~*&\^%\$#!]/', '_', lcfirst($value)));
    return $value;
  }

}
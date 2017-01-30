<?php

use lib\Gedcom;

$options = getopt('f::g:r::');

spl_autoload_register(function ($class) {
  $classFile = str_replace('\\', '/', $class) . '.php';

  if (!file_exists($classFile)) {
    throw new Exception('no class file');
  }

  include $classFile;
});

if (!isset($options['g'])) {
  throw new Exception('no gedcom file');
}

$gedcomFile = $options['g'];
$format = isset($options['f']) ? $options['f'] : 'prolog';
$fileTo = isset($options['r']) ? $options['r'] : 'result';

Gedcom::create($gedcomFile)->setFormat($format)->parse()->save($fileTo);
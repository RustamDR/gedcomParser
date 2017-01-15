<?php
/*
 * Парсер Gedcom к курсовой работе "Интелектуальные системы"
 * Магистратура МАИ, 2016-1017, 1-ый семестр
 * rustd@yandex.ru
 * Дасаев Р.Р.
 *
 * Вызов из командной строки:
 * пример:
 * php parser.php -fprolog -groyal_family.ged -rfamily
 * парсить в result.pl:
 * parser.php -groyal_family.ged
 *
 *    -f - формат результата
 *    -fprolog    - пролог формат <file>.pl
 *    -fjson      - json формат   <file>.json
 *    -fxml       - xml формат    <file>.xml
 *
 *    -g      - gedcom файл для парсинга
 *    -r      - result файл, по-умолчанию result
 */

use lib\Gedcom;

$options = getopt('f::g:r::');

spl_autoload_register(function ($class) {
  include str_replace('\\', '/', $class) . '.php';
});

if (!isset($options['g'])) {
  throw new Exception('no gedcom file');
}

$gedcomFile = $options['g'];
$format = isset($options['f']) ? $options['f'] : 'prolog';
$fileTo = isset($options['r']) ? $options['r'] : 'result';

Gedcom::create($gedcomFile)->setFormat($format)->parse()->save($fileTo);
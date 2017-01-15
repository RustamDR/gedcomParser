<?php

namespace lib;

/**
 * Конвертер GEDCOM в json, prolog, xml форматы
 * Class Gedcom
 */
class Gedcom extends GedcomTags
{
  /**
   * Исходный файл GEDCOM
   * @var string
   */
  protected $_file;

  /**
   * Данные файла GEDCOM по строкам в массиве
   * @var array
   */
  protected $_data = [];

  /**
   * Формат выходных данных: 'json' | 'prolog' | 'xml'
   * @var string
   */
  protected $_format = 'prolog';

  /**
   * Узлы дерева
   * @var null
   */
  protected $_nodes = null;

  /**
   * Gedcom constructor.
   * @param $fileFrom
   */
  protected function __construct($fileFrom)
  {
    $this->_file = $fileFrom;
  }

  /**
   * Инициализация
   */
  protected function init()
  {
    $this->_data = file($this->_file);
  }

  /**
   * Задание формата выходных данных
   * @param $format
   * @return $this
   * @throws \Exception
   */
  public function setFormat($format)
  {
    if (false === key_exists($format, $this->_formats())) {
      throw new \Exception('Incorrect output format');
    }
    $this->_format = $format;

    return $this;
  }


  /**
   * Перечень возможных форматов
   * @return array
   */
  protected function _formats()
  {
    return [
        'prolog' => 'pl',
        'xml'    => 'xml',
        'json'   => 'json',
    ];
  }

  /**
   * Фабричный метод - создание парсера
   * @param $gedcomFile
   * @return static
   */
  public static function create($gedcomFile)
  {
    $gedcom = new static($gedcomFile);
    $gedcom->init();

    return $gedcom;
  }

  /**
   * Процесс парсинга
   * @param null $node
   * @return $this
   */
  public function parse($node = null)
  {
    if (!$this->_data) {
      return $this;
    }

    // инициализация массива для найденных совпадений в регулярном выражении
    $matches = [];

    // парсим по строкам
    while ($this->_data) {

      // текущая строка
      $string = trim(array_shift($this->_data));

      // проверяем правила
      foreach ($this->tagPatterns() as $pattern) {

        // если правило не совпало, то пропускаем строку
        if (!preg_match($pattern, $string, $matches)) {
          continue;
        }

        // убираем первый элемент - так как это сама совпавшая строка
        array_shift($matches);

        // текущий уровень
        $currentLevel = array_shift($matches);
        if (isset($prevLevel) && $currentLevel < $prevLevel && !is_null($node)) {
          array_unshift($this->_data, $string);

          return $this;
        }

        // запомним текущий уровень
        $prevLevel = $currentLevel;

        // второе совпадение может быть либо id сущности, либо атрибут сущности
        $tag = trim(array_shift($matches));
        $value = null;

        // проверим на Id
        $id = $this->getId($tag);
        if (!is_null($id)) {
          // если найден Id, то второй это тег сущности
          $tag = array_shift($matches);
        } elseif ($matches) {
          // если первый не Id, то это значение атрибута $tag
          $value = array_shift($matches);
        }

        // приведем тэг к нижнему регистру
        $tag = strtolower($tag);

        // метода для найденного тэга, которые попадают в парсинг (если описан метод в классе)
        $tagMethod = $this->tagParseMethod($tag);
        // если метода парсинга тега нет, то пропускаем
        if (false === method_exists($this, $tagMethod)) {
          continue;
        }

        // если нашли Id, то это новая сущность (если нет значения то тоже сущность HEAD)
        if (!is_null($id) || is_null($value)) {
          $newNode = $this->{$tagMethod}($id);
        }

        if (isset($newNode)) {
          // новая сущность, для него парсим вложенные элемента
          $this->parse($newNode);

          continue;
        }

        // если нашли атрибут для сущности, то парсим его
        if ($node && $value) {
          // если в классе описано свойство, которое парсим, то запоминаем его
          if (property_exists($node, $tag)) {
            // проверяем значение является ссылкой на другую сущность или нет
            $referenceId = $this->getId($value);
            if ($referenceId) {
              // если это сущность
              $node->{$tag}[] = $this->getReferenceById($tag, $referenceId);
            } else {
              // просто значение
              $node->{$tag} = trim($value);
            }
          }
        }
      }
    }

    return $this;
  }

  /**
   * Имя метода
   * @param $tag
   * @return string
   */
  protected function tagParseMethod($tag)
  {
    return strtolower($tag) . 'Parse';
  }

  /**
   * Попытка получить Id из тэга
   * @param $tag
   * @return int|null
   */
  protected function getId($tag)
  {
    $m = [];
    if (preg_match('/@[A-Z]([0-9]+)@/', $tag, $m)) {
      return (int)$m[1];
    }

    return null;
  }

  /**
   * @param $id
   * @return Person
   */
  protected function indiParse($id)
  {
    if (!isset($this->_nodes['indi'][$id])) {
      $this->_nodes['indi'][$id] = new Person();
    }

    return $this->_nodes['indi'][$id];
  }

  /**
   * @param $id
   * @return Family
   */
  protected function famParse($id)
  {
    if (!isset($this->_nodes['fam'][$id])) {
      $this->_nodes['fam'][$id] = new Family();
    }

    return $this->_nodes['fam'][$id];
  }

  /**
   * Паттерны тэгов
   * @return array
   */
  protected function tagPatterns()
  {
    return [
        '/([0-9])+\s+(@[A-Z][0-9]+@|[A-Z]+)\s*(.*)?/',
    ];
  }

  /**
   * Сохранение в результирующий файл
   * @param $fileTo
   */
  public function save($fileTo)
  {
    $fileTo .= $this->_fileType();

    if (file_exists($fileTo)) {
      unlink($fileTo);
    }

    $class = '\\format\\' . ucfirst($this->_format) . 'Format';

    if (class_exists($class) && method_exists($class, 'save')) {
      $class = new $class;
      $class->save($this->_nodes, $fileTo);
    }
  }

  /**
   * @return string
   */
  protected function _fileType()
  {
    $formats = $this->_formats();

    return '.' . $formats[$this->_format];
  }

  /**
   * Поиск сущности по id
   * @param $tag
   * @param $id
   * @return null
   */
  protected function getReferenceById($tag, $id)
  {
    $referenceRules = GedcomTags::referenceRules();

    $tag = isset($referenceRules[$tag]) ? $referenceRules[$tag] : $tag;
    if (!isset($this->_nodes[$tag][$id])) {
      $tagMethod = $this->tagParseMethod($tag);
      if (method_exists($this, $tagMethod)) {
        $this->_nodes[$tag][$id] = $this->{$tagMethod}($id);
      }
    }

    if (isset($this->_nodes[$tag][$id])) {
      return $this->_nodes[$tag][$id];
    }

    return $id;
  }


}
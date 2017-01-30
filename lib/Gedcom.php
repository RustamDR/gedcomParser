<?php

namespace lib;

use tag\aclasses\AObject;
use tag\aclasses\AProperty;

/**
 * Конвертер GEDCOM в json, prolog, xml форматы
 * Class Gedcom
 */
class Gedcom
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
   * Кол-во строк в файле
   * @var integer
   */
  protected $_length;

  /**
   * Номер текущей строки
   * @var integer
   */
  protected $_strNum;

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
    $this->_length = count($this->_data) - 1;
    $this->_strNum = -1;
  }

  /**
   * Задание формата выходных данных
   * @param $format
   * @return $this
   * @throws \Exception
   */
  public function setFormat($format)
  {
    // если не задан формат вывода, то ошибка
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
   * @param AObject|AProperty|null $node
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
    while ($this->_strNum < $this->_length) {
      // прибавляем номер строки
      $this->_strNum++;

      // текущая строка
      $string = trim($this->_data[$this->_strNum]);

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

        // если нет сущности, для которой можно добавлять атрибуты, то просто пропускаем
        if (is_null($node) && $currentLevel > 0) {
          continue;
        }

        // если текущий уровень стал менее или равен уровню текущей сущности, то текущая сущность отпарсилась
        if ($node && $currentLevel <= $node->level) {
          $this->_strNum--;

          return;
        }

        // второе совпадение может быть либо id сущности, либо атрибут сущности (тэг)
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

        // если нашли Id, то это новая сущность (если нет значения то тоже сущность HEAD)
        $tagClass = $this->getTagClass($tag);
        $newNode = $this->factory($tagClass, $id, $currentLevel);
        // получили новый объект по тегу
        if ($newNode) {
          // теперь парсим данные для новой сущности тэга, пока не выйдем на его уровень
          $this->parse($newNode);

          if ($newNode instanceof AObject) {
            continue;
          }
          $value = $newNode;
          unset($newNode);
        }

        // если не нашли атрибут для сущности или нет значения, то пропускаем
        if (is_null($node) || !$value) {
          continue;
        }

        // если в классе не описано свойство тэга, топропускаем
        if (false === property_exists($node, $tag)) {
          continue;
        }

        // если значение стало сущностью, запоминаем в свойстве объекта и дальнейшее пропускаем
        if (is_object($value)) {
          $node->{$tag} = $value;
          continue;
        }

        // если свойство - это ссылка на другой объект, то присваиваем свойству объект по его Id
        $referenceId = $this->getId($value);
        if ($referenceId) {
          // если это сущность
          $this->setReferenceById($node, $tag, $referenceId, $currentLevel);
        } else {
          // иначе просто строковое значение
          $node->{$tag} = trim($value);
        }
      }
    }

    return $this;
  }

  /**
   * Проверка на возможность автозагрузки класса для тега
   * @param string  $className
   * @param integer $id
   * @param integer $level
   * @return AObject|AProperty|null
   */
  protected function factory($className, $id, $level)
  {
    try {
      class_exists($className, true);

      return $id ? $className::getObject($id, $level) : $className::getObject($level);
    } catch (\Exception $e) {
      return null;
    }
  }

  protected function getTagClass($tag)
  {
    return 'tag\\' . ucfirst($tag);
  }

  /**
   * Попытка получить Id из тэга
   * @param string $tag
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
   * Паттерны тэгов
   * @return array
   */
  protected function tagPatterns()
  {
    return [
        '/([0-9])+\s+(@[A-Z][0-9]+@)\s*([A-Z]+)?/', // паттерн поиска тега вида: "0 @S5@ SOUR"  0 - уровень 5 - ид, SOUR - тэг
        '/([0-9])+\s+([A-Z]+)\s*(.*)?/',            // паттерн поиска аттрибутов для основого тэга например, "2 PAGE Volume 8, page 63" 2 - уровень, PAGE - имя атрибута, Volume 8 - значение атрибута
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
      $class->save($fileTo);
    }
  }

  /**
   * Расширение файла по его формату
   * @return string
   */
  protected function _fileType()
  {
    $formats = $this->_formats();

    return '.' . $formats[$this->_format];
  }

  /**
   * Поиск сущности по id
   * @param AObject|AProperty $node
   * @param string            $tag
   * @param integer           $id
   * @param integer           $level
   * @return mixed
   */
  protected function setReferenceById($node, $tag, $id = null, $level = 0)
  {
    if (true === is_null($node)) {
      return;
    }

    if (false === property_exists($node, $tag)) {
      return;
    }

    $value = $id;
    $isCollection = false;
    $referenceRules = $node->referenceRules();

    if (isset($referenceRules[$tag])) {

      $referenceTag = $referenceRules[$tag];
      $isCollection = is_array($node->{$tag});
      /** @var AObject $tagClass */
      $tagClass = $this->getTagClass($referenceTag);
      $value = $this->factory($tagClass, $id, $level);
    }

    if ($isCollection) {
      array_push($node->{$tag}, $value);
    } else {
      $node->{$tag} = $value;
    }
  }

}
<?php

namespace format;

use format\interfaces\AFormat;

/**
 * Сохранение в формате JSON
 * Class JsonFormat
 * @package format
 */
class JsonFormat extends AFormat
{

  /**
   * @inheritdoc
   */
  public function save($file)
  {
    file_put_contents($file, json_encode(['indi' => $this->_indies, 'fam' => $this->_fams]));
  }

}
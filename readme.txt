 Вызов из командной строки:
 
 Пример:
  php parser.php -fprolog -groyal_family.ged -rfamily
  парсить в result.pl:
  parser.php -groyal_family.ged
 
 Опции:
     -f<format> - формат результата
      -fprolog    - пролог формат <file>.pl
      -fjson      - json формат   <file>.json
      -fxml       - xml формат    <file>.xml
 
     -g      - gedcom файл для парсинга
     -r      - result файл, по-умолчанию result

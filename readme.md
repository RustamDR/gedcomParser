# Парсер Gedcom

Вызов из командной строки:

### Вызов из командной строки
php parser.php

### Параметры
*  -f    формат результирующего файла, по-умолчанию prolog (prolog|json|xml)
*  -g    gedcom файл для парсинга
*  -r    result файл, по-умолчанию result

### Примеры вызова
php parser.php -groyal_family.ged -rfamily
парсить gedcom-файл royal_family.ged в результирующий пролог-файл family.pl

php parser.php -fjson -gged/royal_family.ged -rresult/family
парсить gedcom-файл из ged/royal_family.ged в результирующий json-файл result/family.json


### Настройка тэгов для парсинга

Парсинг происходит по настроенным тэгам в map:

objects - классы с описанием какие свойства тэгов парсить
properties - свойства, которые являются составными, привязываются к objects

# Парсер Gedcom

 Вызов из командной строки:
 
 ## Name
    
    php parser.php

 ## Synopsys
    
    [OPTION]...[FILE]...

 ## Description
 
    -f<prolog|json|xml> - формат результирующего файла, по-умолчанию prolog
    -g                  - gedcom файл для парсинга
    -r                  - result файл, по-умолчанию result

 ### Examples
 
    php parser.php -groyal_family.ged -rfamily
        парсить gedcom-файл royal_family.ged в результирующий пролог-файл family.pl

    php parser.php -fjson -gged/royal_family.ged -rresult/family
        парсить gedcom-файл из ged/royal_family.ged в результирующий json-файл result/family.json

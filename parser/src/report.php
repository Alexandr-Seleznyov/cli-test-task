#!/usr/bin/php
<?php

require_once "autoload.php";

use CliTestTask\Parser\Classes\ConsoleCommands;

/*
|--------------------------------------------------------------------------
| options
|--------------------------------------------------------------------------
| report - Имя класса.
|   Содержит логику поиска необходимых значений.
|
|   Необходимые условия:
|   namespace CliTestTask\Parser\Classes\Reports;
|   implements ReportsInterface
|
|
| modelsData - Имя класса.
|   Содержит логику записи и вывода данных.
|
|   Необходимые условия:
|   namespace CliTestTask\Parser\Classes\ModelsData;
|   implements ModelDataInterface
|
| isDomain - boolean. Необязательный параметр. Преобразует аргумент (любой url) в домен
*/
$options = [
    'report' => 'Images',
    'modelsData' => 'DataArray',
    'isDomain' => true,
];


$consoleCommands = new ConsoleCommands($argv, $options);

print_r($consoleCommands->parse());

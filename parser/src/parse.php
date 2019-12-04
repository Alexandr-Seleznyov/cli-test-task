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
*/
$options = [
    'report' => 'Images',
    'modelsData' => 'FileCsv',
];


$consoleCommands = new ConsoleCommands($argv, $options);

print_r($consoleCommands->parse());

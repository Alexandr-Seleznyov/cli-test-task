<?php

namespace CliTestTask\Parser\Classes\ModelsData;


use CliTestTask\Parser\Interfaces\ModelDataInterface;

class FileCsv implements ModelDataInterface
{
    protected $filePath;


    public function __construct()
    {
        $this->setFilePath();
    }


    public function insert(Array $row)
    {
        $file = fopen($this->filePath, "a");

        fputcsv($file, $row, ';');

        fclose($file);
    }


    public function select()
    {
        return 'Result file: '. $this->filePath;
    }


    protected function setFilePath()
    {
        if (isset($this->filePath)) return $this->filePath;

        $path = dirname( getcwd(), 1);
        $extension='csv';

        $newPatth = $path.'\files';
        if(!is_dir($newPatth)) mkdir($newPatth);

        do {
            $name = md5(microtime() . rand(0, 9999));
            $file = $newPatth . '\\' . $name . '.' . $extension;
        } while (file_exists($file));

        fopen($file, 'w');
        $this->filePath = $file;
    }

}
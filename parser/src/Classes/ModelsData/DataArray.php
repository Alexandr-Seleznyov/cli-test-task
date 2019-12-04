<?php

namespace CliTestTask\Parser\Classes\ModelsData;


use CliTestTask\Parser\Interfaces\ModelDataInterface;

class DataArray implements ModelDataInterface
{
    protected $result = [];


    public function insert(Array $row)
    {
        $this->result[] = $row;
    }


    public function select()
    {
        echo PHP_EOL;
        echo 'Result:'.PHP_EOL;
        echo '--------------------------------------------------------------------------------'.PHP_EOL;
        echo 'Source   ->   Link image'.PHP_EOL;
        echo '--------------------------------------------------------------------------------'.PHP_EOL;

        foreach($this->result as $value){
            echo $value[0].'   ->   '.$value[1].PHP_EOL;
        }

        echo '--------------------------------------------------------------------------------'.PHP_EOL;
    }
}
<?php

namespace CliTestTask\Parser\Classes;


class ConsoleCommands
{
    protected $args;
    protected $options;
    protected $request;
    protected $url;

/*
|-----------------------------------------------------------------------------
| options - массив - ['report' => 'className', 'modelsData' => 'className']
|-----------------------------------------------------------------------------
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
    public function __construct(Array $args, Array $options)
    {
        if(array_key_exists('isDomain', $options) && $options['isDomain'] === true){
            if(!strstr($args[1],"://")) $args[1] = "http://".$args[1];
            $args[1] = parse_url($args[1])['host'];
        }
        $this->args = $args;
        $this->options = $options;
    }


    /**
     * @return mixed|string
     */
    protected function getUrl()
    {
        if(isset($this->url)) return $this->url;

        $this->url = $this->args[1];
        if(!strstr($this->args[1],"://")) $this->url = "http://".$this->args[1];
        if(substr($this->url, -1) === '/')  $this->url = substr($this->url, 0, -1);

        return $this->url;
    }


    /**
     * @return array
     */
    protected function validation()
    {
        $result = [
            'isValid' => true
        ];

        if(count($this->args) < 2){
            $result = [
                'isValid' => false,
                'message' => "One argument is missing.",
            ];
        }

        $request = new Request($this->getUrl());

        $validationUrl = $request->validationUrl();

        if(!$validationUrl['isValid']){
            $result = [
                'isValid' => false,
                'message' => $validationUrl['message'],
            ];
        }

        return $result;
    }


    /**
     * @return mixed|string
     */
    public function parse()
    {
        $valid = $this->validation();

        if(!$valid['isValid']) return $valid['message'];

        $parser = new Parser($this->getUrl(), $this->options);

        return $parser->getResultParse();
    }
}

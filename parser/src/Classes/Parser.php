<?php

namespace CliTestTask\Parser\Classes;


class Parser
{
    protected $url;
    protected $domainName;
    protected $modelData;
    protected $report;
    protected $regExpLink = '!<a.*?href=\"?\'?([^ \"\'>]+)\"?\'?.*?>(.*?)</a>!is';
    protected $allLinks = [];


    public function __construct($url, Array $options)
    {
        $this->url = $url;
        $this->domainName = parse_url($url)['host'];

        $classNameModelData = 'CliTestTask\Parser\Classes\ModelsData\\'.$options['modelsData'];
        $this->modelData = new $classNameModelData;

        $classNameReport = 'CliTestTask\Parser\Classes\Reports\\'.$options['report'];
        $this->report = new $classNameReport;
    }


    /**
     * @param $url
     * @return array
     */
    protected function getLinks($url)
    {
        $request = new Request($url);

        preg_match_all($this->regExpLink, $request->getHtmlCode(), $foundArray);

        $foundArray = $foundArray[1];
        $foundArray = $this->convertLinks($foundArray);
        $foundArray = $this->removeNotDomainLinks($foundArray);
        $foundArray = $this->removeExcessLinks($foundArray);

        return $foundArray;
    }


    /**
     * @param array $links
     * @return array
     */
    protected function convertLinks(Array $links)
    {

        $arrDelete = ['http://', 'https://'];

        foreach($links as $key => $value){

            if($value === '/') $value = '';
            if(!strstr($value,"://"))  $value = $this->domainName.$value;
            if(substr($value, -1) === '/')  $value = substr($value, 0, -1);

            foreach($arrDelete as $scheme){
                $value = str_replace($scheme, '', $value);
            }

            $links[$key] = $value;
        }

        return $links;
    }


    /**
     * @param array $links
     * @return array
     */
    protected function removeExcessLinks(Array $links)
    {
        $result = [];
        foreach($links as $key => $value){
            if(!in_array($value, $this->allLinks)) {
                $result[] = $value;
                $this->allLinks[] = $value;
            }
        }

        return $result;
    }


    /**
     * @param array $links
     * @return array
     */
    protected function removeNotDomainLinks(Array $links)
    {
        $result = [];
        foreach($links as $key => $value){
            if(explode("/", $value)[0] !== $this->domainName) continue;
            $result[] = $value;
        }

        return $result;
    }


    /**
     * @return string
     */
    public function getResultParse()
    {
        $url = $this->convertLinks([$this->url])[0];
        echo 'Please wait ... '.PHP_EOL;
        $this->removeExcessLinks([$url]);
        $this->insertData($url);
        $this->compileData($url);
        echo 'Done.'.PHP_EOL;

        return $this->modelData->select();
    }



    protected function compileData($url)
    {
        $links = $this->getLinks($url);

        foreach($links as $value){
            $this->insertData($value);
            $this->compileData($value);
        }
    }


    protected function insertData($url)
    {
        $url = 'http://'.$url;
        $list = $this->report->getResult($url);

        foreach ($list as $value){
            $this->modelData->insert($value);
        }
    }

}

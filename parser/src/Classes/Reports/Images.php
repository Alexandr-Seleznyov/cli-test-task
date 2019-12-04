<?php

namespace CliTestTask\Parser\Classes\Reports;


use CliTestTask\Parser\Classes\Request;
use CliTestTask\Parser\Interfaces\ReportsInterface;

class Images implements ReportsInterface
{
//    protected $regExpLink = '/(img|src)=("|\')[^"\'>]+/i';
    protected $regExpLink = "/(?<=url\()[a-z.\/]*(?=\))/i";


    public function getResult($url)
    {
        $request = new Request($url);

        $result = [];
        preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $request->getHtmlCode(), $media);
        unset($data);
        $data = preg_replace('/(img|src)("|\'|="|=\')(.*)/i', "$3", $media[0]);

        foreach ($data as $urlImg) {
            $info = pathinfo($urlImg);
            if (isset($info['extension'])) {
                if (($info['extension'] == 'jpg') ||
                    ($info['extension'] == 'jpeg') ||
                    ($info['extension'] == 'gif') ||
                    ($info['extension'] == 'png')) {

                    $urlImg = $this->convertLink($urlImg, $url);
                    $result[] = [$url, $urlImg];
                }
            }
        }

        return $result;
    }


    protected function convertLink($urlImg, $url)
    {
        if(!strstr($urlImg,"://"))  $urlImg = 'http://'.parse_url($url)['host'].'/'.$urlImg;
        return $urlImg;
    }
}
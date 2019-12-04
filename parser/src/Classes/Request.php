<?php

namespace CliTestTask\Parser\Classes;


class Request
{
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }


    /**
     * @return array
     */
    public function validationUrl()
    {
        $result = [
            'isValid' => true
        ];

        $checkUrl = $this->checkUrl();

        if(!$checkUrl['isCheck']) {
            $result = [
                'isValid' => false,
                'message' => $checkUrl['message'],
            ];
        }

        return $result;
    }


    /**
     * @return array
     */
    protected function checkUrl()
    {
        $result = [
            'isCheck' => true,
        ];

        $urlHeaders = @get_headers($this->url);

        if(!strpos($urlHeaders[0], '200')) {
            $result = [
                'isCheck' => false,
                'message' => 'Page not found',
            ];
        }

        return $result;
    }


    public function getUrl()
    {
        return $this->url;
    }


    /**
     * @return string
     */
    public function getHtmlCode()
    {
        $result = 'No connection at this URL';

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $this->url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $result = curl_exec($curl);
            curl_close($curl);
        }

        return $result;
    }

}
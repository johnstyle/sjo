<?php

namespace sJo\Rest\Service\Flickr;

abstract class Flickr
{
    const API_URL = 'https://api.flickr.com/services/rest/';
    const API_FORMAT = 'json';

    private $apiKey;
    private $apiSecret;

    public function __construct ($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    protected function method ($method, $class = null)
    {
        if (is_null($class)) {

            $reflectionClass = new \ReflectionClass(get_called_class());
            $class = strtolower($reflectionClass->getShortName());
        }

        return 'flickr.' . $class . '.' . $method;
    }

    protected function request ($parameters = array())
    {
        $url = self::API_URL . '?' . http_build_query(array_merge(array(
            'api_key' => $this->apiKey,
            'format' => self::API_FORMAT,
            'nojsoncallback' => 1
        ), $parameters));

        $response = file_get_contents($url);

        return $this->response($response);
    }

    protected function response ($response)
    {
        switch (self::API_FORMAT) {

            case 'json':
                $response = json_decode($response);
                break;
        }

        return $response;
    }
}

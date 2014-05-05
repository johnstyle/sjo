<?php

namespace sJo\Rest\Service\Flickr;

class Flickr
{
    const API_URL = 'https://api.flickr.com/services/rest/';
    const API_KEY = 'b9dd0f35d8ae9e94fdaaacda316a5038';
    const API_SECRET = null;

    public function search ($text, $limit = 5)
    {
        $response = $this->request(array(
            'method' => 'flickr.photos.search',
            'text' => $text,
            'per_page' => $limit
        ));

        $photos = array();

        if ($response) {

            foreach ($response->photos->photo as $photo) {

                $photos[] = new Photo($photo);
            }
        }

        return $photos;
    }

    public function request ($parameters = array())
    {
        $url = self::API_URL . '?' . http_build_query(array_merge(array(
            'api_key' => self::API_KEY,
            'format' => 'json',
            'nojsoncallback' => 1
        ), $parameters));

        $content = file_get_contents($url);

        return json_decode($content);
    }
}

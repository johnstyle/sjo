<?php

namespace sJo\Rest\Service\Flickr;

use sJo\Rest\Service\Flickr\Model\Photo;

class Photos extends Flickr
{
    public function search ($text, $per_page = 5)
    {
        return $this->request(array(
            'method' => $this->method('search'),
            'text' => $text,
            'per_page' => $per_page
        ));
    }

    public function getRecent ($per_page = 5)
    {
        return $this->request(array(
            'method' => $this->method('getRecent'),
            'per_page' => $per_page
        ));
    }

    protected function response ($response)
    {
        $response = parent::response($response);

        $photos = array();

        if ($response) {

            foreach ($response->photos->photo as $photo) {

                $photos[] = new Photo($photo);
            }
        }

        return $photos;
    }
}

<?php

namespace sJo\Rest\Service\Flickr\Model;

use sJo\Object\Assign;
use sJo\Object\Entity;

class Photo
{
    use Assign;
    use Entity;

    protected $id;
    protected $owner;
    protected $secret;
    protected $server;
    protected $farm;
    protected $title;
    protected $ispublic;
    protected $isfriend;
    protected $isfamily;

    public function getUrl ($size = 'b')
    {
        return 'http://farm' . $this->farm . '.staticflickr.com/'
            . $this->server . '/'
            . $this->id . '_' . $this->secret . '_' . $size . '.jpg';
    }
}

<?php

namespace sJo\Image\Component;

trait ExifTrait
{
    protected $exif;

    protected function readExif ()
    {
        $this->exif = exif_read_data($this->getFile(), 0, true);

        return $this->exif ? true : false;
    }

    protected function getExifValue ($domain, $name)
    {
        if (isset($this->exif[$domain][$name])) {

            return $this->exif[$domain][$name];
        }

        return null;
    }
}

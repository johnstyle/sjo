<?php

namespace sJo\Image;

use sJo\Object\Entity;

class ImageAnalyzer extends Image
{
    use Component\ExifTrait;
    use Component\RgbTrait;
    use Entity;

    protected $hash;
    protected $width;
    protected $height;
    protected $size;
    protected $mime;
    protected $pixels;
    protected $resolution;
    protected $cameraBrand;
    protected $cameraModel;
    protected $software;
    protected $author;
    protected $datetime;
    protected $source;

    public function __construct ($file)
    {
        parent::__construct($file);

        $this->hash = hash_file('sha256', $this->getFile());
    }

    public function setResolution($x = null, $y = null)
    {
        if (!is_null($x)
            && !is_null($y)) {

            $this->resolution = ((int)$x + (int)$y) / 2;
        }
    }

    public function run ()
    {
        echo '|setExif';
        $this->setExif();
        $this->setRgb();
    }

    protected function setRgb ()
    {
        echo '|findRgbKmeans';
        $this->findRgbKmeans();
    }

    protected function setExif ()
    {
        if ($this->readExif()) {

            $this->size = $this->getExifValue('FILE', 'FileSize');
            $this->mime = $this->getExifValue('FILE', 'MimeType');
            $this->software = $this->getExifValue('IFD0', 'Software');
            $this->datetime = date('Y-m-d H:i:s', strtotime($this->getExifValue('IFD0', 'DateTime')));
            $this->cameraBrand = $this->getExifValue('IFD0', 'Make');
            $this->cameraModel = $this->getExifValue('IFD0', 'Model');
            $this->author = $this->getExifValue('IFD0', 'Artist');

            $this->setResolution(
                $this->getExifValue('IFD0', 'XResolution'),
                $this->getExifValue('IFD0', 'YResolution')
            );
        }
    }
}

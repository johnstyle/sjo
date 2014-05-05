<?php

namespace sJo\Image;

class ImageAnalyzer extends Image
{
    use Component\ExifTrait;
    use Component\RgbTrait;

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

    public function __get($name)
    {
        return $this->{$name};
    }

    public function __set($name, $value)
    {
        if (!is_array($value)) {

            $value = utf8_encode($value);
        }

        $this->{$name} = $value;
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
        $this->setHash();
        $this->setExif();
        $this->setRgb();
    }

    protected function setHash ()
    {
        $this->hash = hash_file('sha256', $this->getFile());
    }

    protected function setRgb ()
    {
        $this->findRgbMain();
        $this->findRgbEnvironment();
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

<?php

namespace sJo\Image;

class Image
{
    const RESAMPLE_WIDTH = 128;

    private $file;
    private $resource;
    private $resample = array();

    public function __construct ($file)
    {
        // From web
        if (preg_match("#^https?://#", $file)) {

            $url = $file;
            $file = sys_get_temp_dir() . '/' . basename($url);

            if (!is_file($file)) {

                copy($url, $file);
            }
        }

        $this->file = $file;

        if (!is_file($this->file)) {

            throw new ImageException('Can not load ' . $file);
        }

        if (mime_content_type($this->file) !== 'image/jpeg') {

            throw new ImageException('Is not a valid format ' . $file);
        }

        $this->resource = imagecreatefromjpeg($this->file);

        $this->width = imagesx($this->resource);
        $this->height = imagesy($this->resource);
        $this->pixels = $this->width * $this->height;

        $this->resample();

        if (!is_resource($this->resource)) {

            throw new ImageException('Is not a valid resource ' . $file);
        }
    }

    public function getFile ()
    {
        return $this->file;
    }

    public function resample ()
    {
        if ($this->width > self::RESAMPLE_WIDTH) {

            $src = $this->resource;

            $resize = ImageMath::resize($this->width, $this->height, self::RESAMPLE_WIDTH);

            $this->resource = imagecreatetruecolor($resize['width'], $resize['height']);
            imagecopyresampled($this->resource, $src, 0, 0, 0, 0, $resize['width'], $resize['height'], $this->width, $this->height);
        }

        $this->resample['width'] = imagesx($this->resource);
        $this->resample['height'] = imagesy($this->resource);
        $this->resample['pixels'] = $this->resample['width'] * $this->resample['height'];
    }

    protected function getResource ()
    {
        return $this->resource;
    }

    protected function getResample ($name)
    {
        return isset($this->resample[$name]) ? $this->resample[$name] : null;
    }

    protected function saveTo ($filename, $quality = 60)
    {
        imagejpeg($this->resource, $filename, $quality);
    }

    public function delete ()
    {
        unlink($this->file);
    }

    protected function pixelsIterator (callable $callback)
    {
        $x = 0;

        do {

            $y = 0;

            do {

                $callback($x, $y);

                $y++;

            } while($y < $this->getResample('height'));

            $x++;

        } while($x < $this->getResample('width'));
    }
}

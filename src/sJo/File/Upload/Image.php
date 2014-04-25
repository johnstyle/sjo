<?php

/**
 * Gestion des Vues
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\File\Upload;

use sJo\File\Upload;
use sJo\File\UploadException;
use sJo\Libraries\I18n;
use sJo\Request\Request;

/**
 * Gestion des Vues
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class Image extends Upload
{
    const IMAGE_MAX_HEIGHT = 1024;
    const IMAGE_MAX_WIDTH = 1024;
    const IMAGE_QUALITY = 70;

    protected $fileTypes = array(
        'image/gif',
        'image/jpeg',
        'image/png'
    );

    /**
     * @return bool
     * @throws UploadException
     */
    public function isSecure ()
    {
        parent::isSecure();

        $imageInfo = getimagesize(Request::env('FILES')->{$this->requestName}->tmp_name->val());

        if ($imageInfo['mime'] != Request::env('FILES')->{$this->requestName}->type->val()) {

            throw new UploadException(I18n::__('The file is not in the correct format.'));
        }

        return true;
    }

    /**
     * @param $dest
     *
     * @throws \sJo\File\UploadException
     * @return bool
     */
    public function copy ($dest)
    {
        $src = Request::env('FILES')->{$this->requestName}->tmp_name->val();

        list($width, $height, $type) = getimagesize($src);

        $newWidth = $width;
        $newHeight = $height;
        $source =  null;

        switch ($type) {

            case IMG_GIF:
                $source = imagecreatefromgif($src);
                break;

            case IMG_JPG:
                $source = imagecreatefromjpeg($src);
                break;

            case IMG_PNG:
                $source = imagecreatefrompng($src);
                break;
        }

        if (is_null($source)) {

            throw new UploadException(I18n::__('Error image mime'));
        }

        if ($height > self::IMAGE_MAX_HEIGHT) {

            $newWidth = (self::IMAGE_MAX_HEIGHT / $height) * $width;
            $newHeight = self::IMAGE_MAX_HEIGHT;
        }

        if ($width > self::IMAGE_MAX_WIDTH) {

            $newHeight = (self::IMAGE_MAX_WIDTH / $width) * $height;
            $newWidth = self::IMAGE_MAX_WIDTH;
        }

        $img = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled($img, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        if(!imagejpeg($img, $dest, static::IMAGE_QUALITY)) {

            throw new UploadException(I18n::__('Error image create.'));
        }

        return true;
    }
}

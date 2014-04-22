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

namespace sJo\File;

use sJo\Exception\Exception;
use sJo\Libraries\I18n;

/**
 * Gestion des Vues
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class UploadException extends Exception
{
    /**
     * Constructeur
     *
     * @param null $msg
     * @param int $code
     * @return UploadException
     */
    public function __construct($msg = null, $code = 0)
    {
        if (is_null($msg)) {

            $msg = $this->message($code);
        }

        parent::__construct($msg, $code);
    }

    /**
     * @param int $code
     *
     * @return string
     */
    private function message($code)
    {
        switch ($code) {

            case UPLOAD_ERR_INI_SIZE:
                $message = I18n::__('The uploaded file exceeds the upload_max_filesize directive in php.ini');
                break;

            case UPLOAD_ERR_FORM_SIZE:
                $message = I18n::__('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form');
                break;

            case UPLOAD_ERR_PARTIAL:
                $message = I18n::__('The uploaded file was only partially uploaded');
                break;

            case UPLOAD_ERR_NO_FILE:
                $message = I18n::__('No file was uploaded');
                break;

            case UPLOAD_ERR_NO_TMP_DIR:
                $message = I18n::__('Missing a temporary folder');
                break;

            case UPLOAD_ERR_CANT_WRITE:
                $message = I18n::__('Failed to write file to disk');
                break;

            case UPLOAD_ERR_EXTENSION:
                $message = I18n::__('File upload stopped by extension');
                break;

            default:
                $message = I18n::__('Unknown upload error');
                break;
        }

        return $message;
    }
}

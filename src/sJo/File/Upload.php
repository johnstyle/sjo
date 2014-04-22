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
abstract class Upload
{
    const MIN_FILE_SIZE = 1024;
    const MAX_FILE_SIZE = 10485760;

    protected $fileTypes = array();

    protected $requestName;

    public function __construct($name)
    {
        $this->requestName = $name;
    }

    /**
     * @param $name
     * @param $dest
     *
     * @return bool
     */
    public static function quickCopy($name, $dest)
    {
        $upload = new static ($name);
        if ($upload->isSubmit()
            && $upload->isSecure()) {

            return $upload->copy($dest);
        }

        return false;
    }


    public function isSubmit ()
    {
        return Request::env('FILES')->{$this->requestName}->exists()
            && Request::env('FILES')->{$this->requestName}->tmp_name->val();
    }

    /**
     * @throws UploadException
     * @return bool
     */
    public function isSecure ()
    {
        if (!Request::env('FILES')->{$this->requestName}->tmp_name->val()) {

            throw new UploadException(I18n::__('The file does not exist.'));
        }

        if (!Request::env('FILES')->{$this->requestName}->error->eq(UPLOAD_ERR_OK)) {

            throw new UploadException(null, Request::env('FILES')->{$this->requestName}->error->val());
        }

        if (Request::env('FILES')->{$this->requestName}->size->val() < static::MIN_FILE_SIZE) {

            throw new UploadException(I18n::__('The file is too small.'));
        }

        if (Request::env('FILES')->{$this->requestName}->size->val() > static::MAX_FILE_SIZE) {

            throw new UploadException(I18n::__('The file is too large.'));
        }

        if (!in_array(Request::env('FILES')->{$this->requestName}->type->val(), $this->fileTypes)) {

            throw new UploadException(I18n::__('The file is not in the correct format.'));
        }

        return true;
    }

    /**
     * @param $dest
     *
     * @return bool
     */
    public function copy ($dest)
    {
        return File::copy(Request::env('FILES')->{$this->requestName}->tmp_name->val(), $dest);
    }
}

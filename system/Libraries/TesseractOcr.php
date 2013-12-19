<?php
/**
 * sJo
 *
 * PHP version 5
 *
 * @package  sJo
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Libraries;

abstract class TesseractOcr
{
    public static function recognize($originalImage)
    {
        $tifImage = self::convertImageToTif($originalImage);
        $configFile = self::generateConfigFile(func_get_args());
        $outputFile = self::executeTesseract($tifImage, $configFile);
        $recognizedText = self::readOutputFile($outputFile);
        self::removeTempFiles($tifImage, $outputFile, $configFile);
        return $recognizedText;
    }

    public static function convertImageToTif($originalImage)
    {
        $tifImage = ROOT_TMP . '/tesseract-ocr-tif-' . rand() . '.tif';
        exec("convert -colorspace gray +matte $originalImage $tifImage");
        return $tifImage;
    }

    public static function generateConfigFile($arguments)
    {
        $configFile = ROOT_TMP . '/tesseract-ocr-config-' . rand() . '.conf';
        exec("touch $configFile");
        $whitelist = self::generateWhitelist($arguments);
        if (!empty($whitelist)) {
            $fp = fopen($configFile, 'w');
            fwrite($fp, "tessedit_char_whitelist $whitelist");
            fclose($fp);
        }
        return $configFile;
    }

    public static function generateWhitelist($arguments)
    {
        array_shift($arguments);
        $whitelist = '';
        foreach ($arguments as $chars)
            $whitelist .= join('', (array)$chars);
        return $whitelist;
    }

    public static function executeTesseract($tifImage, $configFile)
    {
        $outputFile = ROOT_TMP . '/tesseract-ocr-output-' . rand();
        exec("tesseract $tifImage $outputFile nobatch $configFile 2> /dev/null");
        return $outputFile . '.txt';
    }

    public static function readOutputFile($outputFile)
    {
        return trim(file_get_contents($outputFile));
    }

    public static function removeTempFiles()
    {
        array_map("unlink", func_get_args());
    }
}

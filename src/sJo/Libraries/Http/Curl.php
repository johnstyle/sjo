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

namespace sJo\Libraries\Http;

use sJo\Libraries\Arr;
use sJo\Libraries\Csv;

class Curl
{
    private $id;
    private $cookieFile;
    private $resource;
    private $response;
    private $info;
    private $cache;

    public function __construct()
    {
        $this->id = md5(microtime());
        $this->cookieFile = SJO_ROOT_TMP . '/' . $this->id . '.cookie';
        $this->resource = curl_init();

        $this->setOption(CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/21.0');
        $this->setOption(CURLOPT_ENCODING, 'gzip,deflate');
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLOPT_HEADER, false);
        $this->setOption(CURLOPT_AUTOREFERER, true);
        $this->setOption(CURLOPT_TIMEOUT, 10);
        $this->setOption(CURLOPT_CONNECTTIMEOUT, 10);
        $this->setOption(CURLOPT_FOLLOWLOCATION, true);
        $this->setOption(CURLOPT_MAXREDIRS, 10);
        $this->setOption(CURLOPT_VERBOSE, false);
        $this->setOption(CURLOPT_COOKIEJAR, $this->cookieFile);
    }

    public function setCache($time)
    {
        $this->cache = $time;
    }

    public function setOption($option, $value = false)
    {
        if (is_array($option)) {
            curl_setopt_array($this->resource, $option);
        } else {
            curl_setopt($this->resource, $option, $value);
        }
    }

    public function request($url, $post = false)
    {
        $hash = md5($url . serialize($post));
        $cacheFile = SJO_ROOT_TMP . '/' . $hash . '.cache';
        if (!$this->cache || !file_exists($cacheFile) || filemtime($cacheFile) >= (time() + $this->cache)) {
            if (preg_match("#^https://#i", $url)) {
                $this->setOption(CURLOPT_SSL_VERIFYPEER, false);
                $this->setOption(CURLOPT_SSL_VERIFYHOST, false);
            }

            if ($post) {
                if (is_array($post)) {
                    $post = http_build_query($post);
                }
                $this->setOption(CURLOPT_POST, true);
                $this->setOption(CURLOPT_POSTFIELDS, $post);
            }

            if (file_exists($this->cookieFile)) {
                $this->setOption(CURLOPT_COOKIEFILE, $this->cookieFile);
            }

            $this->setOption(CURLOPT_URL, $url);

            $this->response = curl_exec($this->resource);
            $this->info = curl_getinfo($this->resource);

            if ($this->cache) {
                file_put_contents($cacheFile, serialize((object) array(
                    'response' => $this->response,
                    'info' => $this->info
                )));
            }
        } else {
            $cache = unserialize(file_get_contents($cacheFile));
            $this->response = $cache->response;
            $this->info = $cache->info;
        }
    }

    public function info($name = false)
    {
        return Arr::getTree($this->info, $name);
    }

    public function response($format = null, $options = null, callable $callback = null)
    {
        if ($this->info('http_code') == 200) {
            if ($callback) {
                $this->response = call_user_func($callback, $this->response);
            }
            switch ($format) {
                case 'json':
                    return json_decode($this->response);
                    break;
                case 'csv':
                    return Csv::arrayFromRaw($this->response, $options);
                    break;
                default:
                    return $this->response;
                    break;
            }
        }
        return false;
    }

    public function __destruct()
    {
        if (is_resource($this->resource)) {
            curl_close($this->resource);
        }
        if (file_exists($this->cookieFile)) {
            unlink($this->cookieFile);
        }
    }
}

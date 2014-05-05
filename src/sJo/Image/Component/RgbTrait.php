<?php

namespace sJo\Image\Component;

use sJo\Image\ImageMath;

trait RgbTrait
{
    protected $rgb = array(
        'pixels' => null,
        'colors' => null,
        'main' => null,
        'primary' => null,
    );

    protected function findRgbPixels ()
    {
        $this->rgb['pixels'] = array();

        $this->pixelsIterator(function ($x, $y) {

            $this->rgb['pixels'][$y][$x] = $this->getRgbColor($x, $y);
        });
    }

    protected function findRgbColors ()
    {
        $this->rgb['colors'] = array();

        $this->pixelsIterator(function ($x, $y) {

            $rgbStr = $this->rgbToStr($this->getRgbColor($x, $y));

            if (!isset($this->rgb['colors'][$rgbStr])) {

                $this->rgb['colors'][$rgbStr] = 0;
            }

            $this->rgb['colors'][$rgbStr]++;
        });

        arsort($this->rgb['colors']);
    }

    protected function findRgbEnvironment ()
    {
        $r = 0;
        $g = 0;
        $b = 0;
        $i = 0;

        $this->rgb['environment'] = array();

        $this->pixelsIterator(function ($x, $y) use(&$r, &$g, &$b, &$i) {

            $rgb = $this->getRgbColor($x, $y);

            $r += $rgb['red'];
            $g += $rgb['green'];
            $b += $rgb['blue'];

            $i++;
        });

        $r = round($r / $i);
        $g = round($g / $i);
        $b = round($b / $i);

        $this->rgb['environment'] = array(
            'primary' => array(
                'red' => $r,
                'green' => $g,
                'blue' => $b
            ),
            'brightness' => ImageMath::percent(ImageMath::brightness($r, $g, $b))
        );
    }

    protected function findRgbMain ($percentlimit = 1)
    {
        $this->rgb['main'] = array();

        if (!isset($this->rgb['colors'])) {

            $this->findRgbColors();
        }

        if ($this->rgb['colors']) {

            $this->rgb['main'] = $this->extractRgbClosest($this->rgb['colors'], 64);
            $this->rgb['main'] = $this->extractRgbPrimaries($this->rgb['colors']);

            arsort($this->rgb['main']);

            $pixels = imagesx($this->getResource()) * imagesy($this->getResource());

            foreach ($this->rgb['main'] as $rgbStr=>&$percent) {

                $percent = round(($percent * 100) / $pixels, 2);

                if (!is_null($percentlimit)
                    && $percent < $percentlimit) {

                    unset($this->rgb['main'][$rgbStr]);
                }
            }
        }
    }

    protected function extractRgbPrimaries ($colors)
    {
        $primary = array();

        foreach ($colors as $rgbStr=>$nbr) {

            $rgb = $this->strToRgb($rgbStr);

            foreach (array('red', 'green', 'blue') as $color) {

                if ($rgb[$color] == max($rgb)) {

                    if (!isset($primary[$color])) {

                        $primary[$color] = array(
                            'rgb' => $rgb,
                            'total' => $nbr,
                            'count' => 1
                        );

                    } else {

                        $primary[$color] = array(
                            'rgb' => $this->getRgbAvg(array(
                                array(
                                    'rgb' => $rgb,
                                    'total' => $nbr
                                ),
                                array(
                                    'rgb' => $primary[$color]['rgb'],
                                    'total' => $primary[$color]['total']
                                ),
                            )),
                            'total' => $primary[$color]['total'] + $nbr,
                            'count' => $primary[$color]['count'] + 1
                        );
                    }
                }
            }
        }

        $colors = array();

        if (count($primary)) {

            foreach ($primary as $item) {

                $rgbStr = $this->rgbToStr(array_map('round', $item['rgb']));

                $colors[$rgbStr] = $item['total'];
            }
        }

        return $colors;
    }

    protected function extractRgbClosest ($colors , $distance = 64)
    {
        $closest = array();

        foreach ($colors as $rgbStr=>$nbr) {

            $rgb = $this->strToRgb($rgbStr);

            if (count($closest)) {

                $minDistance = null;
                $minClosest = null;

                foreach ($closest as $rgbClosestStr=>$closestAvg) {

                    $rgbDistance = $this->getRgbDistance($rgb, $this->strToRgb($rgbClosestStr));

                    if ($rgbDistance < $distance
                            && (is_null($minDistance)
                            || $rgbDistance < $minDistance)) {

                        $minDistance = $rgbDistance;
                        $minClosest = $rgbClosestStr;
                    }
                }

                if (!is_null($minClosest)) {

                    $closest[$minClosest] = array(
                        'rgb' => $this->getRgbAvg(array(
                            array(
                                'rgb' => $rgb,
                                'total' => $nbr
                            ),
                            array(
                                'rgb' => $closest[$minClosest]['rgb'],
                                'total' => $closest[$minClosest]['total']
                            ),
                        )),
                        'total' => $closest[$minClosest]['total'] + $nbr,
                        'count' => $closest[$minClosest]['count'] + 1
                    );

                    continue;
                }
            }

            $closest[$rgbStr] = array(
                'rgb' => $rgb,
                'total' => $nbr,
                'count' => 1
            );
        }

        $colors = array();

        if (count($closest)) {

            foreach ($closest as $item) {

                $rgbStr = $this->rgbToStr(array_map('round', $item['rgb']));

                $colors[$rgbStr] = $item['total'];
            }
        }

        return $colors;
    }

    protected function getRgbColor ($x, $y, $radius = 0)
    {
        $width = imagesx($this->getResource());
        $height = imagesy($this->getResource());

        $xStart = $x - $radius;
        if ($xStart < 0) {
            $xStart = 0;
        }

        $xEnd = $x + $radius;
        if ($xEnd <= $width) {
            $xEnd = $width - 1;
        }

        $yStart = $y - $radius;
        if ($yStart < 0) {
            $yStart = 0;
        }

        $yEnd = $y + $radius;
        if ($yEnd >= $height) {
            $yEnd = $height - 1;
        }

        $r = 0;
        $g = 0;
        $b = 0;
        $a = 0;
        $i = 0;

        for($xIndex = $xStart; $xIndex <= $xEnd; $xIndex++) {

            for($yIndex = $yStart; $yIndex <= $yEnd; $yIndex++) {

                $rgb = imagecolorat($this->getResource(), $xIndex, $yIndex);

                $r += ($rgb >> 16) & 0xFF;
                $g += ($rgb >> 8) & 0xFF;
                $b += ($rgb) & 0xFF;
                $a += ($rgb >> 24) & 0x7F;

                $i++;
            }
        }

        return array(
            'red' => $r > 0  ? round($r / $i) : 0,
            'green' => $g > 0  ? round($g / $i) : 0,
            'blue' => $b > 0  ? round($b / $i) : 0,
            'alpha' => $a > 0  ? round($a / $i) : 0
        );
    }

    protected function getRgbRange ($rgb, $range = 64)
    {
        $rgbAvg = array();

        foreach($rgb as $i=>$color) {

            for ($n = 0; $n <= 255; $n+=$range) {

                if ($color >= $n
                    && $color < $n+$range) {

                    $rgbAvg[$i] = $n;

                    break;
                }
            }
        }

        return $rgbAvg;
    }

    protected function getRgbAvg ($colors)
    {
        $r = 0;
        $g = 0;
        $b = 0;
        $total = 0;

        foreach ($colors as $color) {

            $total += $color['total'];
        }

        foreach ($colors as $color) {

            $ratio =  $color['total'] / $total;

            $r += $color['rgb']['red'] * $ratio;
            $g += $color['rgb']['green'] * $ratio;
            $b += $color['rgb']['blue'] * $ratio;
        }

        return array(
            'red' => $r,
            'green' => $g,
            'blue' => $b
        );
    }

    protected function getRgbDistance ($rgb1, $rgb2 = array(0,0,0))
    {
        return sqrt(pow($rgb1['red'] - $rgb2['red'], 2) + pow($rgb1['green'] - $rgb2['green'], 2) + pow($rgb1['blue'] - $rgb2['blue'], 2) / 255);
    }

    public function rgbToStr ($rgb, $separator = ',')
    {
        return $rgb['red'] . $separator . $rgb['green'] . $separator . $rgb['blue'];
    }

    public function strToRgb ($rgbStr, $separator = ',')
    {
        list($r, $g, $b) = explode($separator, $rgbStr);

        return array(
            'red' => $r,
            'green' => $g,
            'blue' => $b
        );
    }
}

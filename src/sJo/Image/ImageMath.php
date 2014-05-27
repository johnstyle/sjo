<?php

namespace sJo\Image;

abstract class ImageMath
{
    public static function resize ($w, $h, $mw)
    {
        $ratio = ($mw / $w);

        return array(
            'width' => round($w * $ratio),
            'height' => round($h * $ratio)
        );
    }

    public static function brightness ($r, $g, $b)
    {
        return (float) (0.241*($r^2)) + (0.691*($g^2)) + (0.068*($b^2));
    }

    public static function percent ($value, $decimal = 2)
    {
        return (float) round(($value * 100) / 256, $decimal);
    }
}

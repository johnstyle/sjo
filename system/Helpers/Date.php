<?php

namespace sJo\Helpers;

function DateInterval($begin, $end)
{
    $dates      = array();
    $datetime1  = new \DateTime($begin);
    $datetime2  = new \DateTime($end);
    $interval   = $datetime1->diff($datetime2)->days;
    for($i = 0; $i <= $interval; $i++){
        $dates[] = date('Y-m-d', strtotime('+ '.$i.' days '.$begin));
    }
    return $dates;
}

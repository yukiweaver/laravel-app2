<?php

use Carbon\Carbon;

if (!function_exists('carbon')) {
    function carbon($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $tz = null)
    {
        return Carbon::create($year, $month, $day, $hour, $minute, $second, $tz);
    }
}
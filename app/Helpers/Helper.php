<?php

use Carbon\Carbon;

if (!function_exists('carbon')) {
    function carbon($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $tz = null)
    {
        return Carbon::create($year, $month, $day, $hour, $minute, $second, $tz);
    }
}

/**
 * 小数点第三桁を四捨五入
 */
if (!function_exists('calculation')) {
  function calculation($secondTime)
  {
    return round($secondTime / 3600, 2);
  }
}

/**
 * 時間を10段階表示（10:30 -> 10.50）
 */
if (!function_exists('timeTenDiv')) {
  function timeTenDiv($time)
  {
    $hour = Carbon::parse($time)->format('H');
    $minute = floatval(Carbon::parse($time)->format('i'));
    $minute = round($minute / 60, 2);
    $sum = $hour + $minute;
    return $sum;
  }
}
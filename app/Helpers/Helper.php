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

/**
 * 時間外時間計算
 * @param $is_next_day 翌日フラグ
 * @param $time 終了予定時間
 * @param $time2 指定勤務終了時間
 */
if (!function_exists('overtimeCalculation')) {
  function overtimeCalculation($isNextDay, $time, $time2)
  {
    if ($isNextDay) {
      return round((floatval($time->format('H')) + floatval($time->format('i') / 60)) - (floatval($time2->format('H')) + floatval($time2->format('i') / 60)) + 24.0, 2);
    } else {
      return round((floatval($time->format('H')) + floatval($time->format('i') / 60)) - (floatval($time2->format('H')) + floatval($time2->format('i') / 60)), 2);
    }
  }
}

/**
 * ログインユーザか判定
 */
if (!function_exists('isCurrentUser')) {
  function isCurrentUser($userId)
  {
    if (auth()->user()->id == $userId) {
      return true;
    }
    return false;
  }
}
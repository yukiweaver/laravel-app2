<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /**
     * 勤怠データを所有するユーザー取得
     */
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    /**
     * 1ヶ月分の日付を取得
     */
    public static function getOneMonthDays($firstDay, $lastDay)
    {
      $oneMonthDays = self::where('attendance_day', '>=', $firstDay)
                      ->where('attendance_day', '<=', $lastDay)
                      ->get();
      return $oneMonthDays;
    }
}

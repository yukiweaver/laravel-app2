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
     * 1ヶ月分の勤怠データを取得
     */
    public static function getOneMonthData($firstDay, $lastDay)
    {
      $oneMonthData = self::where('attendance_day', '>=', $firstDay)
                      ->where('attendance_day', '<=', $lastDay)
                      ->get();
      return $oneMonthData;
    }
}

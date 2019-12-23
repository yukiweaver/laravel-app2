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
     * 勤怠データに紐付く残業申請データ取得
     */
    public function overwork()
    {
      return $this->hasOne('App\Overwork');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'start_time', 
      'end_time', 
      'note',
  ];

    /**
     * 1ヶ月分の勤怠データを取得
     */
    public static function getOneMonthData($firstDay, $lastDay)
    {
      $oneMonthData = self::where('attendance_day', '>=', $firstDay)
                      ->where('attendance_day', '<=', $lastDay)
                      ->where('user_id', auth()->user()->id)
                      ->get();
      return $oneMonthData;
    }

    /**
     * 指定の日付の勤怠データを取得
     * @return App\Attendance
     */
    public static function getAttendanceCurrentData($userId, $currentDay)
    {
      $attendance = self::where('user_id', $userId)->where('attendance_day', $currentDay)->first();
      return $attendance;
    }

    /**
     * 指定ユーザの本日の勤怠データを取得（出社中の場合のみ）
     */
    public static function getTodayData($userId, $today)
    {
      $attendance = self::where('attendance_day', $today)->where('user_id', $userId)->where('end_time', null)->whereNotNull('start_time')->first();
      return $attendance;
    }

    /**
     * ユーザidと勤怠idをキーにして、勤怠データを取得
     */
    public static function getAttendance($attendanceId)
    {
      $attendance = self::where('user_id', auth()->user()->id)->where('id', $attendanceId)->first();
      return $attendance;
    }
}

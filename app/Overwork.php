<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Overwork extends Model
{
  /**
   * 残業データを所有するユーザー取得
   */
  public function user()
  {
    return $this->belongsTo('App\User');
  }

  /**
   * 残業データに紐づく勤怠データ取得
   */
  public function attendance()
  {
    return $this->belongsTo('App\Attendance');
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'scheduled_end_time', 
    'is_next_day', 
    'business_description',
    'instructor_id',
    'apply_overtime_status',
  ];

  /**
   * 日付で検索
   */
  public static function findLikeAttendanceDay($attendanceDay)
  {
    $userId = auth()->user()->id;
    $overwork = self::where('user_id', $userId)->where('scheduled_end_time', 'LIKE', "$attendanceDay%")->first();
    if (empty($overwork)) {
      return null;
    }
    return $overwork;
  }
}

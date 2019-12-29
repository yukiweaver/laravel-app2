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
    'attendance_id',
    'scheduled_end_time', 
    'is_next_day', 
    'business_description',
    'instructor_id',
    'apply_overtime_status',
    'attendance_day',
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

  /**
   * ログインしている上長に申請されている残業申請の数をカウント
   */
  public static function countOverwork($userId)
  {
    $count = self::where('instructor_id', $userId)->where('apply_overtime_status', '1')->count();
    return $count;
  }

  /**
   * ログインしている上長に申請されている残業申請データを取得
   */
  public static function findApprovalOverwork($userId)
  {
    $overwork = self::where('instructor_id', $userId)->where('apply_overtime_status', '1')->orderBy('user_id')->get();
    return $overwork;
  }
}

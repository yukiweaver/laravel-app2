<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OneMonthAttendance extends Model
{
  /**
   * 月勤怠データを所有するユーザー取得
   */
  public function user()
  {
    return $this->belongsTo('App\User');
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'apply_status',
    'instructor_id',
    'target_month',
  ];

  /**
   * ログインユーザが申請しているデータ1件取得
   */
  public static function findApplyData($userId, $targetMonth)
  {
    $oneMonthAttendance = self::where('user_id', $userId)->where('target_month', 'LIKE', "$targetMonth%")->first();
    if (empty($oneMonthAttendance)) {
      return null;
    }
    return $oneMonthAttendance;
  }

  /**
   * ログインしている上長に申請されている月勤怠申請の数を取得
   */
  public static function countOneMonthAttendance($userId)
  {
    $count = self::where('instructor_id', $userId)->where('apply_status', '1')->count();
    return $count;
  }

  /**
   * ログインしている上長に申請されている月勤怠申請データを取得
   */
  public static function findApprovalOneMonthAttendance($userId)
  {
    $oneMonthAttendance = self::where('instructor_id', $userId)->where('apply_status', '1')->orderBy('user_id')->get();
    return $oneMonthAttendance;
  }
}

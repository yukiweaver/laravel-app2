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
}

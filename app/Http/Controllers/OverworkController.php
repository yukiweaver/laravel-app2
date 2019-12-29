<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HTTP\Requests\OverworkRequest;
use App\Attendance;
use App\User;
use App\Overwork;
use Carbon\Carbon;

class OverworkController extends Controller
{
 /**
  * コンストラクタ
  * 継承したControllerクラスのmiddleware()を利用する
  */
  public function __construct()
  {
    // ログイン状態を判断するミドルウェア
    $this->middleware('auth');
  }


  /**
   * 残業申請アクション
   */
  public function overtimeApplication(OverworkRequest $request)
  {
    $flgData = $request->flg_data;
    // adminは不可
    if ($flgData['admin_flg']) {
      return redirect('/index');
    }
    
    $user = auth()->user();
    $userId = $user->id;
    $params = $request->all();
    $attendance = Attendance::getAttendance($params['attendance_id']);
    $attendanceDay = $attendance->attendance_day;
    $scheduledEndTime = Carbon::parse($attendanceDay . $params['scheduled_end_time'])->format('Y-m-d H:i:s');
    
    $params['attendance_day'] = $attendanceDay;
    $params['scheduled_end_time'] = $scheduledEndTime;
    $params['user_id'] = $userId;
    $params['apply_overtime_status'] = '1';
    $params['attendance_day'] = $attendanceDay;
    
    // レコードが存在しないならINSERT、存在するならUPDATE
    $overwork = Overwork::findLikeAttendanceDay($attendanceDay);
    if (is_null($overwork)) {
      $overwork = new Overwork;
    }
    if ($overwork->fill($params)->save()) {
      return redirect("/show")->with('flash_message', config('const.SUCCESS_APPLY_OVERTIME'));
    } else {
      return redirect("/show")->with('error_message', config('const.ERR_APPLY_OVERTIME'));
    }
  }

  /**
   * 残業承認アクション
   */
  public function overtimeApproval(OverworkRequest $request)
  {
    $flgData = $request->flg_data;
    // adminは不可
    if ($flgData['admin_flg']) {
      return redirect('/index');
    }
    $user = auth()->user();
    $userId = $user->id;
    $params = $request->overwork;
    $currentDay = $request->current_day;
    $total = 0; // ループ数
    $i = 0; // 処理した数
    foreach ($params as $key => $val) {
      $total ++;
      // 「変更」にチェックなしはスキップ
      if ($val['change'] == 0) {
        continue;
      }
      Overwork::find($key)->fill($params[$key])->save();
      $i ++;
    }
    return redirect("/show?current_day=$currentDay")->with('flash_message', "${total}件中${i}件更新しました。");
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HTTP\Requests\OverworkRequest;
use App\Attendance;
use App\User;
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
  public function overtimeApplication(Request $request)
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
    dd($params);
  }
}

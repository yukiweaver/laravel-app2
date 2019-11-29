<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Attendance;
use Illuminate\Support\Facades\Config;
use Session;

class AttendanceController extends Controller
{
  /**
   * 出社時間登録アクション
   */
  public function startTime(Request $request)
  {
    $user = auth()->user();
    $userId = $user->id;
    $today = Carbon::today();

    // POSTか確認
    if (!$request->isMethod('post')) {
      return redirect('/show');
    }

    try {
      $attendance = Attendance::where('user_id', $userId)->where('attendance_day', $today)->first();
      if (empty($attendance)) {
        throw new Exception(config('const.ERR_REGIST_START_TIME'));
      }
      $attendance->start_time = Carbon::now()->format('Y-m-d H:i:s');
      $result = $attendance->save();
      if (!$result) {
        throw new Exception(config('const.ERR_REGIST_START_TIME'));
      }
    } catch (Exception $e) {
      return redirect('/show')->with('error_message', $e->getMessage());
    }

    Session::flash('flash_message', config('const.SUCCESS_REGIST_START_TIME'));
    return redirect('/show');
  }
}

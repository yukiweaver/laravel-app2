<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HTTP\Requests\AttendanceRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Attendance;
use Illuminate\Support\Facades\Config;
use Session;
use Illuminate\Support\Facades\Validator;

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
      $attendance = Attendance::getAttendanceCurrentData($userId, $today);
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

  /**
   * 退社時間登録アクション
   */
  public function endTime(Request $request)
  {
    $user = auth()->user();
    $userId = $user->id;
    $today = Carbon::today();

    // POSTか確認
    if (!$request->isMethod('post')) {
      return redirect('/show');
    }

    try {
      $attendance = Attendance::getAttendanceCurrentData($userId, $today);
      if (empty($attendance)) {
        throw new Exception(config('const.ERR_REGIST_END_TIME'));
      }
      $attendance->end_time = Carbon::now()->format('Y-m-d H:i:s');
      $result = $attendance->save();
      if (!$result) {
        throw new Exception(config('const.ERR_REGIST_END_TIME'));
      }
    } catch (Exception $e) {
      return redirect('/show')->with('error_message', $e->getMessage());
    }

    Session::flash('flash_message', config('const.SUCCESS_REGIST_END_TIME'));
    return redirect('/show');
  }

  /**
   * 勤怠編集アクション
   */
  public function edit(Request $request)
  {
    $user = auth()->user();
    $userId = $user->id;

    // クエリパラメータ チェック
    $validator = $this->validator($request->query());
    if ($validator->fails()) {
      return redirect('/show');
    }

    $currentDay = Carbon::parse($request->input('current_day'));
    $firstDay = $currentDay->copy()->firstOfMonth(); // 月初
    $lastDay = $firstDay->copy()->endOfMonth(); // 月末
    $date = Attendance::getOneMonthData($firstDay, $lastDay);
    $week = ['日', '月', '火', '水', '木', '金', '土'];

    foreach ($date as $d) {
      $d->attendance_day = Carbon::parse($d->attendance_day);
      $d->start_time = $d->start_time ? Carbon::parse($d->start_time) : null;
      $d->end_time = $d->end_time ? Carbon::parse($d->end_time) : null;
    }
    
    $viewParams = [
      'date' => $date,
      'week' => $week,
      'currentDay' => $currentDay->format('Y-m-d'),
    ];
    return view('attendance.edit', $viewParams);
  }

  /**
   * 勤怠更新アクション
   */
  public function update(AttendanceRequest $request)
  {
    $user = auth()->user();
    $userId = $user->id;
    $today = Carbon::today();

    $message = null;
    foreach ($request->attendance as $key => $val) {
      $startTime = $val['start_time'];
      $endTime = $val['end_time'];
      $note = $val['note'];
      if ($startTime > $endTime) {
        $message = config('const.ERR_START_TIME_LARGER');
        break;
      }
      $attendance = Attendance::find($key);
      if (Carbon::parse($attendance->attendance_day) > $today) {
        $dbStartTime = Carbon::parse($attendance->start_time)->format('H:i');
        $dbEndTime = Carbon::parse($attendance->end_time)->format('H:i');
        $dbNote = $attendance->note;
        if ($dbStartTime != $startTime || $dbEndTime != $endTime || $dbNote != $note) {
          $message = config('const.ERR_EDIT_AFTER_TOMORROW');
          break;
        }
      }
    }
    
    if (!is_null($message)) {
      return redirect('/show')->with('error_message', $message);
    }
    return redirect('/show');
  }

  // private

  /**
   * クエリパラメータチェック用
   * 
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  private function validator(array $data)
  {
    $validator = Validator::make($data, [
      'current_day' => 'date|required',
    ]);
    return $validator;
  }
}

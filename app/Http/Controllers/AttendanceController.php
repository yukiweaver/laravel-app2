<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HTTP\Requests\AttendanceRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Attendance;
use App\User;
use Illuminate\Support\Facades\Config;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
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
   * 出社時間登録アクション
   */
  public function startTime(Request $request)
  {
    $flgData = $request->flg_data;
    // adminは不可
    if ($flgData['admin_flg']) {
      return redirect('/index');
    }
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
    $flgData = $request->flg_data;
    // adminは不可
    if ($flgData['admin_flg']) {
      return redirect('/index');
    }
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
    $flgData = $request->flg_data;
    // adminは不可
    if ($flgData['admin_flg']) {
      return redirect('/index');
    }
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
    $date = Attendance::getOneMonthData($firstDay, $lastDay, $userId);
    $week = ['日', '月', '火', '水', '木', '金', '土'];
    $today = Carbon::today();
    $superiors = User::getSuperiorUsers($user->superior_flg);

    foreach ($date as $d) {
      $d->attendance_day = Carbon::parse($d->attendance_day);
      $d->start_time = $d->start_time ? Carbon::parse($d->start_time) : null;
      $d->end_time = $d->end_time ? Carbon::parse($d->end_time) : null;
    }
    
    $viewParams = [
      'date'        => $date,
      'week'        => $week,
      'currentDay'  => $currentDay->format('Y-m-d'),
      'today'       => $today,
      'superiors'   => $superiors,
    ];
    return view('attendance.edit', $viewParams);
  }

  /**
   * 勤怠更新アクション
   */
  public function update(AttendanceRequest $request)
  {
    $flgData = $request->flg_data;
    // adminは不可
    if ($flgData['admin_flg']) {
      return redirect('/index');
    }
    $user = auth()->user();
    $userId = $user->id;
    $today = Carbon::today();
    $currentDay = Carbon::parse($request->current_day)->format('Y-m-d');

    $dbParams = [];
    $message = null;
    foreach ($request->attendance as $key => $val) {
      $startTime = $val['start_time'];
      $endTime = $val['end_time'];
      $note = $val['note'];
      $isNextDay = $val['is_next_day'];
      $instructorId = $val['instructor_id'];
      // 出勤時間、退勤時間、どちらか一方のみ空ならエラー
      if (!empty($startTime) && empty($endTime) || empty($startTime) && !empty($endTime)) {
        $message = config('const.ERR_INVALID_DATA');
        break;
      }
      // 出勤時間 > 退勤時間　でエラー
      if ($startTime > $endTime) {
        $message = config('const.ERR_START_TIME_LARGER');
        break;
      }
      $attendance = Attendance::find($key);
      // 明日以降の編集は不可
      if (Carbon::parse($attendance->attendance_day) > $today) {
        $dbStartTime = $attendance->start_time ? Carbon::parse($attendance->start_time)->format('H:i') : null;
        $dbEndTime = $attendance->end_time ? Carbon::parse($attendance->end_time)->format('H:i') : null;
        $dbNote = $attendance->note;
        if ($dbStartTime != $startTime || $dbEndTime != $endTime || $dbNote != $note) {
          $message = config('const.ERR_EDIT_AFTER_TOMORROW');
          break;
        }
      }
      $dbParams[] = [
        'id' => $key,
        'start_time' => $startTime ? Carbon::parse($attendance->attendance_day . $startTime)->format('Y-m-d H:i:s') : null,
        'end_time' => $endTime ? Carbon::parse($attendance->attendance_day . $endTime)->format('Y-m-d H:i:s') : null,
        'note' => $note,
      ];
    }
    
    if (!is_null($message)) {
      return redirect("/show?current_day=$currentDay")->with('error_message', $message);
    }

    // 更新処理
    $i = 0;
    $dbParam = [];
    foreach ($dbParams as $param) {
      $dbParam[] = [
        'start_time' => $param['start_time'],
        'end_time' => $param['end_time'],
        'note' => $param['note'],
      ];
      Attendance::find($param['id'])->fill($dbParam[$i])->save();
      $i++;
    }

    return redirect("/show?current_day=$currentDay")->with('flash_message', config('const.SUCCESS_REGIST_ATTENDANCE_DATA'));
  }

  /**
   * CSVダウンロードアクション
   */
  public function downloadData(Request $request)
  {
    $flgData = $request->flg_data;
    // adminは不可
    if ($flgData['admin_flg']) {
      return redirect('/index');
    }

    $currentDay = Carbon::parse($request->input('current_day'));
    $firstDay = $currentDay->copy()->firstOfMonth(); // 月初
    $lastDay = $firstDay->copy()->endOfMonth(); // 月末
    $attendances = Attendance::getOneMonthData($firstDay, $lastDay);
    
    $response = new StreamedResponse (function() use ($request, $attendances) {

      $stream = fopen('php://output', 'w');

      //　文字化け回避
      // stream_filter_prepend($stream,'convert.iconv.utf-8/cp932//TRANSLIT');

      // タイトルを追加
      fputcsv($stream, config('const.CSV_DL_HEADER'));

      foreach ($attendances as $attendance) {
        $attendanceDay = $attendance->attendance_day;
        $startTime = $attendance->start_time ? Carbon::parse($attendance->start_time)->format('H:i') : '';
        $endTime = $attendance->end_time ? Carbon::parse($attendance->end_time)->format('H:i') : '';
        $note = $attendance->note;
        fputcsv($stream, [$attendanceDay, $startTime, $endTime, $note]);
      }
      fclose($stream);
    });
    
    $date = $currentDay->format('Y-m');

    $response->headers->set('Content-Type', 'application/octet-stream');
    $response->headers->set('Content-Disposition', "attachment; filename=$date.csv");

    return $response;
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

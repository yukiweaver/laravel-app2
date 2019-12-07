<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HTTP\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use App\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

  public function index(Request $request)
  {
    $flgData = $request->flg_data;
    // admin以外は不可
    if (!$flgData['admin_flg']) {
      return redirect('/show');
    }
    $users = User::all();
    $viewParams = [
      'users' => $users,
    ];
    return view('user.index', $viewParams);
  }

  public function show(Request $request)
  {
    $sessLogin = session()->get('_login');
    $userId = $sessLogin['user_id'];
    $user = User::find($userId);
    
    if (empty($request->input('current_day'))) {
      $currentDay = Carbon::now();
    } else {
      // クエリパラメータありならバリデーションチェック
      $validator = $this->validator($request->query());
      if ($validator->fails()) {
        return redirect('/show');
      }
      $currentDay = Carbon::parse($request->input('current_day'));
    }
    $today = Carbon::today();
    $firstDay = $currentDay->copy()->firstOfMonth(); // 月初
    $lastDay = $firstDay->copy()->endOfMonth(); // 月末
    $lastMonth = $currentDay->copy()->subMonthNoOverflow()->format('Y-m-d'); // １ヶ月前の日付
    $nextMonth = $currentDay->copy()->addMonthNoOverflow()->format('Y-m-d'); // １ヶ月後の日付
    $week = ['日', '月', '火', '水', '木', '金', '土'];

    $dbParams = [];
    for ($i = 0; true; $i++) {
        $day = $firstDay->addDays($i);
        $firstDay = $currentDay->copy()->firstOfMonth(); // 月初に戻す
        // テーブルに値が存在しないか確認
        if (!DB::table('attendances')->where('attendance_day', $day)->where('user_id', $userId)->exists()) {
          $data = [
            'user_id' => $userId,
            'attendance_day' => $day,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
          ];
        }
        if ($day > $lastDay) {
            break;
        }
        if (!empty($data)) {
          array_push($dbParams, $data);
        }
    }
    if (!empty($dbParams)) {
      DB::transaction(function () use ($dbParams) {
        DB::table('attendances')->insert($dbParams);
      });
    }

    $date = Attendance::getOneMonthData($firstDay, $lastDay);
    // Carbonインスタンスに変更（出勤日数もカウント）
    $attendanceDays = 0;
    foreach ($date as $d) {
      $d->attendance_day = Carbon::parse($d->attendance_day);
      $d->start_time = $d->start_time ? Carbon::parse($d->start_time) : null;
      $d->end_time = $d->end_time ? Carbon::parse($d->end_time) : null;
      if (isset($d->start_time)) {
        $attendanceDays++;
      }
    }
    
    $viewParams = [
      'user' => $user,
      'date' => $date,
      'week' => $week,
      'lastMonth' => $lastMonth,
      'nextMonth' => $nextMonth,
      'firstDay' => $firstDay,
      'lastDay' => $lastDay,
      'today' => $today,
      'currentDay' => $currentDay->format('Y-m-d'),
      'attendanceDays' => $attendanceDays,
    ];
    return view('user.show', $viewParams);
  }

  public function edit()
  {
    $sessLogin = session()->get('_login');
    $userId = $sessLogin['user_id'];
    $user = User::find($userId);
    return view('user.edit', ['user' => $user]);
  }

  public function update(UserRequest $request, $id)
  {
    $user = User::find($id);
    // バリデーションを通過したリクエストを取得
    $validated = $request->validated();
    $user->update([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'belong' => $validated['belong'],
      'password' => $validated['password'],
    ]);
    return redirect('/show');
  }

  public function adminUpdate(UserRequest $request)
  {
    $flgData = $request->flg_data;
    // admin以外は不可
    if (!$flgData['admin_flg']) {
      return redirect('/show');
    }
    $params = $request->all();
    $user = User::find($params['user_id']);
    try {
      $result = $user->fill($params)->save();
      if (!$result) {
        throw new Exception(config('const.ERR_UPDATE'));
      }
    } catch (Exception $e) {
      return redirect('/index')->with('error_message', $e->getMessage());
    }

    return redirect('/index')->with('flash_message', config('const.SUCCESS_UPDATE'));
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
      'current_day' => 'date',
    ]);
    return $validator;
  }
}

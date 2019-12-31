<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HTTP\Requests\OneMonthAttendanceRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\User;
use App\OneMonthAttendance;
use Carbon\Carbon;

class OneMonthAttendanceController extends Controller
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
  * 申請アクション
  */
  public function apply(OneMonthAttendanceRequest $request)
  {
    $flgData = $request->flg_data;
    // adminは不可
    if ($flgData['admin_flg']) {
      return redirect('/index');
    }
    $user = auth()->user();
    $userId = $user->id;
    $params = $request->all();
    $params['user_id'] = $userId;
    $params['apply_status'] = '1';
    $params['target_month'] = Carbon::parse($params['current_day']);
    try {
      // 上長ユーザが存在するか確認
      User::findorFail($params['instructor_id']);
    } catch (ModelNotFoundException $e) {
      return redirect("/show?current_day={$params['current_day']}")->with('error_message', config('const.ERR_INVALID_REQUEST'));
    }
    // データがなければINSERT、あればUPDATE
    $oneMonthAttendance = OneMonthAttendance::findApplyData($userId, $params['target_month']->format('Y-m'));
    if (is_null($oneMonthAttendance)) {
      $oneMonthAttendance = new OneMonthAttendance();
    }
    if (!$oneMonthAttendance->fill($params)->save()) {
      return redirect("/show?current_day={$params['current_day']}")->with('error_message', config('const.ERR_APPLY_ONE_MONTH_ATTENDANCE'));
    }
    return redirect("/show?current_day={$params['current_day']}")->with('flash_message', config('const.SUCCESS_APPLY_ONE_MONTH_ATTENDANCE'));
  }

 /**
  * 承認アクション
  */
  public function approval(OneMonthAttendanceRequest $request)
  {
    $flgData = $request->flg_data;
    // adminは不可
    if ($flgData['admin_flg']) {
      return redirect('/index');
    }
    $user = auth()->user();
    $userId = $user->id;
    $params = $request->one_month_attendance;
    $currentDay = $request->current_day;
    $total = 0; // ループ数
    $i = 0; // 処理数
    foreach ($params as $key => $val) {
      $total ++;
      // 「変更」にチェックなしはスキップ
      if ($val['change'] == 0) {
        continue;
      }
      try {
        $oneMonthAttendance = OneMonthAttendance::findorFail($key);
      } catch (ModelNotFoundException $e) {
        return redirect("/show?current_day=$currentDay")->with('error_message', config('const.ERR_INVALID_REQUEST'));
      }
      $oneMonthAttendance->fill($params[$key])->save();
      $i ++;
    }
    return redirect("/show?current_day=$currentDay")->with('flash_message', "${total}件中${i}件更新しました。");
  }
}

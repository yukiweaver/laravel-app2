<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HTTP\Requests\OneMonthAttendanceRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\User;

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
    try {
      $test = User::findorFail($params['instructor_id']);
    } catch (ModelNotFoundException $e) {
      return redirect("/show?current_day={$params['current_day']}")->with('error_message', config('const.ERR_INVALID_REQUEST'));
    }
    // dd($test);
  }
}

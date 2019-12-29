<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}

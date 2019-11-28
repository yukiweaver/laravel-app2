<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
  // 出社時間登録
  public function start_time()
  {
    $user = auth()->user();
    $userId = $user->id;
  }
}

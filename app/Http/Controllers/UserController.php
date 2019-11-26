<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\HTTP\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use App\Attendance;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

  public function show()
  {
    $sessLogin = session()->get('_login');
    $user_id = $sessLogin['user_id'];
    $user = User::find($user_id);
    // $user = auth()->user();
    // $toDay = Carbon::today();
    $firstDay = Carbon::now()->firstOfMonth();
    $lastDay = $firstDay->copy()->endOfMonth();

    $dbParams = [];
    for ($i = 0; true; $i++) {
        $day = $firstDay->addDays($i);
        $firstDay = Carbon::now()->firstOfMonth(); // 月初に戻す
        // テーブルに値が存在しないか確認
        if (!DB::table('attendances')->where('attendance_day', $day)->where('user_id', $user_id)->exists()) {
          $data = [
            'user_id' => $user_id,
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

    $date = Attendance::getOneMonthDays($firstDay, $lastDay);
    
    $viewParams = [
      'user' => $user,
      'date' => $date,
    ];
    return view('user.show', $viewParams);
  }

  public function edit()
  {
    $sessLogin = session()->get('_login');
    $user_id = $sessLogin['user_id'];
    $user = User::find($user_id);
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
}

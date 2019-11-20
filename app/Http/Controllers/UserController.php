<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\HTTP\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;

class UserController extends Controller
{

  public function show()
  {
    $sessLogin = session()->get('_login');
    $user_id = $sessLogin['user_id'];
    $user = User::find($user_id);
    // $toDay = Carbon::today();
    // $firstDay = $toDay->firstOfMonth();
    // $lastDay = $toDay->lastOfMonth();
    // $date = [];
    // for ($i=0; true; $i++) {
    //   $day = $firstDay->addDays($i);
    //   if ($day > $lastDay) {
    //     break;
    //   }
    //   // array_push($date, $day);
    // }
    // $firstOfMonth = $toDay->firstOfMonth();
    $firstOfMonth = Carbon::now()->firstOfMonth();
    $endOfMonth = $firstOfMonth->copy()->endOfMonth();

    for ($i = 0; true; $i++) {
        $date = $firstOfMonth->addDays($i);
        if ($date > $endOfMonth) {
            break;
        }
        echo $date->format('Y-m-d') . PHP_EOL; //2018-08-01, 2018-08-02, ･･･, 2018-08-30, 2018-8-31
    }
    
    
    $viewParams = ['user' => $user];
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

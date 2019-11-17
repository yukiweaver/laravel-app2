<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\HTTP\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{

  public function show()
  {
    $sessLogin = session()->get('_login');
    $user_id = $sessLogin['user_id'];
    $user = User::find($user_id);
    return view('user/show', ['user' => $user]);
  }

  public function edit()
  {
    $sessLogin = session()->get('_login');
    $user_id = $sessLogin['user_id'];
    $user = User::find($user_id);
    return view('user/edit', ['user' => $user]);
  }

  public function update(UserRequest $request, $id)
  {
    $user = User::find($id);
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

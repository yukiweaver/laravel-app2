<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::resource('book', 'BookController');
// Route::get('/', 'SiteTopController@top');
Auth::routes();

Route::get('/', 'Auth\LoginController@showLoginForm')->name('route');
Route::get('/home', 'HomeController@index')->name('home'); // 名前path route('home')
Route::group(['middleware' => 'admin'], function() {
  Route::get('/edit', 'UserController@edit')->name('edit');
  Route::get('/show', 'UserController@show')->name('show');
  Route::post('/update/{id}', 'UserController@update')->name('update');
  Route::post('attendance/start_time', 'AttendanceController@startTime')->name('start_time');
  Route::post('attendance/end_time', 'AttendanceController@endTime')->name('end_time');
  Route::get('attendance/edit', 'AttendanceController@edit')->name('attendance_edit');
  Route::post('attendance/update', 'AttendanceController@update')->name('attendance_update');
  Route::get('/index', 'UserController@index')->name('index');
  Route::post('/admin_update', 'UserController@adminUpdate')->name('admin_update');
  Route::get('/working_users_list', 'UserController@workingUsersList')->name('working_users_list');
  Route::get('/import_users_input', 'UserController@importUsersInput')->name('import_users_input');
  Route::post('/import_users_complete', 'UserController@importUsersComplete')->name('import_users_complete');
  Route::post('attendance/download_data', 'AttendanceController@downloadData')->name('download_data');
  Route::post('overwork/overtime', 'OverworkController@overtimeApplication')->name('overtime');
  Route::post('overwork/approval', 'OverworkController@overtimeApproval')->name('overtime_approval');
  Route::post('one_month_attendance/apply', 'OneMonthAttendanceController@apply')->name('one_month_attendance_apply');
  Route::post('one_month_attendance/approval', 'OneMonthAttendanceController@approval')->name('one_month_attendance_approval');
  Route::post('attendance/approval', 'AttendanceController@approval')->name('attendance_approval');
  Route::get('attendance/approval_history', 'AttendanceController@approval_history')->name('approval_history');
});


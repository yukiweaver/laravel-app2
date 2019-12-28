<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HTTP\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use App\Attendance;
use App\Overwork;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Hash;

class UserController extends Controller
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
   * ユーザ一覧アクション
   */
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

  /**
   * 勤怠データ表示アクション
   */
  public function show(Request $request)
  {
    $flgData = $request->flg_data;
    // adminは不可
    if ($flgData['admin_flg']) {
      return redirect('/index');
    }
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
    $superiors = User::getSuperiorUsers($user->superior_flg);
    $overworkCount = Overwork::countOverwork();
    $approvalOverwork = Overwork::findApprovalOverwork();
    
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
    // Carbonインスタンスに変更（出勤日数、在社時間の合計もカウント）
    $attendanceDays = 0;
    $totalWorkingTime = 0;
    foreach ($date as $d) {
      $d->attendance_day = Carbon::parse($d->attendance_day);
      $d->start_time = $d->start_time ? Carbon::parse($d->start_time) : null;
      $d->end_time = $d->end_time ? Carbon::parse($d->end_time) : null;
      if (isset($d->start_time)) {
        $attendanceDays++;
      }
      if ($d->start_time !== null && $d->end_time !== null) {
        $totalWorkingTime += $d->start_time->diffInSeconds($d->end_time);
      }
      $overwork = $d->overwork()->first();
      $d->scheduled_end_time = $overwork ? Carbon::parse($overwork->scheduled_end_time) : null;
      $d->business_description = $overwork ? $overwork->business_description : null;
      $d->apply_overtime_status = $overwork ? $overwork->apply_overtime_status : null;
      $d->instructor = $overwork ? User::find($overwork->instructor_id)->name : null;
      $d->is_next_day = $overwork ? $overwork->is_next_day : null;
    }
    $totalWorkingTime = calculation($totalWorkingTime);
    $totalWorkingHours = timeTenDiv($user->basic_work_time) * $attendanceDays; // 総合勤務時間

    if ($approvalOverwork->isNotEmpty()) {
      foreach ($approvalOverwork as $val) {
        $val->designate_end_time = Carbon::parse($val->attendance_day . $val->user->designate_end_time);
        $val->attendance_day = Carbon::parse($val->attendance_day);
        $val->scheduled_end_time = Carbon::parse($val->scheduled_end_time);
      }
    }
    
    $viewParams = [
      'user'                  => $user,
      'date'                  => $date,
      'week'                  => $week,
      'lastMonth'             => $lastMonth,
      'nextMonth'             => $nextMonth,
      'firstDay'              => $firstDay,
      'lastDay'               => $lastDay,
      'today'                 => $today,
      'currentDay'            => $currentDay->format('Y-m-d'),
      'attendanceDays'        => $attendanceDays,
      'totalWorkingTime'      => $totalWorkingTime,
      'totalWorkingHours'     => $totalWorkingHours,
      'superiors'             => $superiors,
      'designateEndTime'      => Carbon::parse($currentDay->format('Y-m-d') . $user->designate_end_time),
      'overWorkCount'         => $overworkCount,
      'approvalOverwork'      => $approvalOverwork,
    ];
    return view('user.show', $viewParams);
  }

  /**
   * ユーザ情報編集アクション
   */
  public function edit()
  {
    $sessLogin = session()->get('_login');
    $userId = $sessLogin['user_id'];
    $user = User::find($userId);
    return view('user.edit', ['user' => $user]);
  }

  /**
   * ユーザ情報更新アクション
   */
  public function update(UserRequest $request, $id)
  {
    $user = User::find($id);
    // バリデーションを通過したリクエストを取得
    $validated = $request->validated();
    $user->update([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'belong' => $validated['belong'],
      'password' => Hash::make($validated['password']),
    ]);
    return redirect('/show');
  }

  /**
   * 管理者用ユーザ情報更新アクション
   */
  public function adminUpdate(UserRequest $request)
  {
    $flgData = $request->flg_data; // middleware
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

  /**
   * 出勤社員一覧アクション
   */
  public function workingUsersList(Request $request)
  {
    $flgData = $request->flg_data; // middleware
    // admin以外は不可
    if (!$flgData['admin_flg']) {
      return redirect('/show');
    }
    $today = Carbon::today()->format('Y-m-d');
    $users = User::all();
    
    $workingUsers = [];
    foreach ($users as $user) {
      $attendance = Attendance::getTodayData($user->id, $today);
      if (is_null($attendance)) {
        continue;
      }
      $workingUser = User::find($attendance->user_id);
      array_push($workingUsers, $workingUser);
    }
    $viewParams = [
      'workingUsers' => $workingUsers,
    ];
    return view('user.working_users_list', $viewParams);
  }

  /**
   * CSVインポート入力アクション
   */
  public function importUsersInput(Request $request)
  {
    $flgData = $request->flg_data; // middleware
    // admin以外は不可
    if (!$flgData['admin_flg']) {
      return redirect('/show');
    }
    $viewParams = [];
    return view('user.import_users_input', $viewParams);
  }

  /**
   * CSVインポート完了アクション
   */
  public function importUsersComplete(UserRequest $request)
  {
    $flgData = $request->flg_data; // middleware
    // admin以外は不可
    if (!$flgData['admin_flg']) {
      return redirect('/show');
    }

    $csvFile = $request->file('csv_file')->store('csv'); // stroge/app/csvに保存

    $fp = fopen(storage_path('app/') . $csvFile, 'r');

    // 1行目（ヘッダ）読み込み
    $headers = fgetcsv($fp);

    $columnsName = [];

    // CSVヘッダ確認
    foreach ($headers as $header) {
      $result = User::retrieveUserColumnsByValue($header);
      if (is_null($result)) {
        fclose($fp);
        Storage::delete($csvFile);
        return redirect(route('import_users_input'))->with('error_message', config('const.ERR_CSV_IMPORT'));
      }
      $columnsName[] = $result;
    }
    
    // 1行ずつ読み込む（ヘッダを除く2行目から）
    $importErrors = [];
    $rows = [];
    $i = 0;
    $successFlg = true;
    while ($row = fgetcsv($fp)) {
      $rows[] = $row;
    }
    $data = [];
    foreach ($rows as $key => $val) {

      $arr = [];
      
      foreach ($val as $k => $v) {
        $arr = $this->csvData($arr, $k, $v);
      }
      $validator = $this->csvImportValidator($arr);
      // バリデーション
      if ($validator->fails()) {
        $importErrors[$i+2] = $validator->errors()->all(); // 何行目かを出すために+2している
        $successFlg = false;
      }

      $i++;
      $arr['password'] = Hash::make($arr['password']);
      $arr['created_at'] = Carbon::now();
      $arr['updated_at'] = Carbon::now();
      $data[] = $arr;
    }

    $viewParams = [
      'importErrors' => $importErrors,
    ];
    
    if ($successFlg) {
      // DB一括登録
      $isResult = User::bulkUserRegist($data);
      $viewParams['isResult'] = $isResult;
    }

    return view('user.import_users_complete', $viewParams);

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

  /**
   * バリデーションの定義（csvインポート用）
   *
   * @return array
   */
  private function csvImportValidator(array $data)
  {
    $validator = Validator::make($data, [
      'name'                      => 'required|string|max:255',
      'email'                     => 'required|string|unique:users|max:255|email:rfc',
      'belong'                    => 'required|string|max:255',
      'number'                    => 'nullable|string|max:255',
      'card_number'               => 'nullable|string|max:255',
      'basic_work_time'           => 'required|string',
      'designate_start_time'      => 'required|string',
      'designate_end_time'        => 'required|string',
      'superior_flg'              => 'required|boolean',
      'admin_flg'                 => 'required|boolean',
      'password'                  => 'required|string|min:8|regex:/^[a-zA-Z0-9-]+$/',
    ]);
    return $validator;
  }

  private function csvData(array $arr, string $k, string $v)
  {
    switch ($k) {
      case 0:
        $arr['name'] = $v;
        break;
      case 1:
        $arr['email'] = $v;
        break;
      case 2:
        $arr['belong'] = $v;
        break;
      case 3:
        $arr['number'] = $v;
        break;
      case 4:
        $arr['card_number'] = $v;
        break;
      case 5:
        $arr['basic_work_time'] = $v;
        break;
      case 6:
        $arr['designate_start_time'] = $v;
        break;
      case 7:
        $arr['designate_end_time'] = $v;
        break;
      case 8:
        $arr['superior_flg'] = $v;
        break;
      case 9:
        $arr['admin_flg'] = $v;
        break;
      case 10:
        $arr['password'] = $v;
        break;
      default:
        break;
    }
    return $arr;
  }
}

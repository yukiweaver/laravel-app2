<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * ユーザーの勤怠データを取得
     */
    public function attendances()
    {
      return $this->hasMany('App\Attendance');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'belong', 
        'designate_start_time',
        'designate_end_time',
        'basic_work_time',
        'number',
        'card_number',
        'csv_file',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * CSVヘッダ項目の定義値があれば定義配列のkeyを返す   
     *
     * @param string $header
     * @param string $encoding
     * @return string|null
     */
    public static function retrieveUserColumnsByValue(string $header , $encoding=null)
    {
      // CSVヘッダとテーブルのカラムを関連付けておく
      $list = [
        'name' => '名前',
        'email' => 'メールアドレス',
        'belong' => '所属',
        'number' => '社員番号',
        'card_number' => 'カード番号',
        'basic_work_time' => '基本勤務時間',
        'designate_start_time' => '指定勤務開始時間',
        'designate_end_time' => '指定勤務終了時間',
        'superior_flg' => '上長フラグ',
        'admin_flg' => '管理者フラグ',
        'password' => 'パスワード',
      ];

      foreach ($list as $key => $value) {
        if ($encoding) {
          if ($header === mb_convert_encoding($value, $encoding)) {
            return $key;
          }
        }
        if ($header === $value) {
          return $key;
        }
      }
      return null;
    }
}

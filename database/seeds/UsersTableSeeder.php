<?php

use Illuminate\Database\Seeder;
use App\User;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // テーブルのクリア
      // DB::table('users')->truncate();

      // 初期データ用意（カラムをキーとする連想配列）
      $users = [
        [
          'name' => 'test_admin',
          'email' => 'test0001@gmail.com',
          'password' => Hash::make('password'),
          'belong' => 'admin',
          'admin_flg' => true,
          'superior_flg' => false,
          'designate_start_time' => Carbon::parse('10:00'),
          'designate_end_time' => Carbon::parse('10:00'),
          'basic_work_time' => Carbon::parse('10:00'),
          'number' => 'aaa',
          'card_number' => 'aaa',
        ],
        [
          'name' => 'superiorA',
          'email' => 'test0002@gmail.com',
          'password' => Hash::make('password'),
          'belong' => 'test',
          'admin_flg' => false,
          'superior_flg' => true,
          'designate_start_time' => Carbon::parse('10:00'),
          'designate_end_time' => Carbon::parse('10:00'),
          'basic_work_time' => Carbon::parse('10:00'),
          'number' => 'bbb',
          'card_number' => 'bbb',
        ],
        [
          'name' => 'superiorB',
          'email' => 'test0003@gmail.com',
          'password' => Hash::make('password'),
          'belong' => 'test',
          'admin_flg' => false,
          'superior_flg' => true,
          'designate_start_time' => Carbon::parse('10:00'),
          'designate_end_time' => Carbon::parse('10:00'),
          'basic_work_time' => Carbon::parse('10:00'),
          'number' => 'ccc',
          'card_number' => 'ccc',
        ],
        [
          'name' => 'test1000',
          'email' => 'test1000@gmail.com',
          'password' => Hash::make('password'),
          'belong' => 'test',
          'admin_flg' => false,
          'superior_flg' => false,
          'designate_start_time' => Carbon::parse('10:00'),
          'designate_end_time' => Carbon::parse('10:00'),
          'basic_work_time' => Carbon::parse('10:00'),
          'number' => 'ddd',
          'card_number' => 'ddd',
        ],
      ];

      // 登録
      foreach ($users as $user) {
        User::create($user);
      }
    }
}

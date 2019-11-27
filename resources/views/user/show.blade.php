@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped table-condensed">
                      <thead>
                        <tr>
                          <th colspan="1">
                          <a href="/show?current_day={{$lastMonth}}" class="btn-sm btn-primary">←</a>
                            &emsp;時間管理表&emsp;
                          <a href="/show?current_day={{$nextMonth}}" class="btn-sm btn-primary">→</a>
                          </th>
                          <th>
                            指定勤務開始時間
                            指定勤務終了時間
                          </th>
                          <th colspan="3">基本時間</th>
                          <th>初日</th>
                        </tr>

                        <tr>
                          <th>所属</th>
                          <th>氏名</th>
                          <th>コード</th>
                          <th>2222</th>
                          <th>出勤日数
                            0日
                          </th>
                          <th>締め</th>
                        </tr>
                      </thead>
                    </table>

                    <div class="btn-group">
                      <a href="#" class="btn btn-primary">勤怠編集</a>
                    </div>

                    <table class="table table-bordered table-striped table-condensed">
                      <thead>
                        <tr>
                          <th rowspan="2">日付</th>
                          <th rowspan="2">曜日</th>
                          <th colspan="2">出社</th>
                          <th colspan="2">退社</th>
                          <th rowspan="2">在社時間</th>
                          <th rowspan="2">備考</th>
                        </tr>
                        <tr>
                          <th>時</th>
                          <th>分</th>
                          <th>時</th>
                          <th>分</th>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach ($date as $d)
                        <tr>
                        <!-- 日付 -->
                        <td>{{$d->attendance_day->format('m/d')}}</td>
                        <!-- 曜日 -->
                        <td>{{$week[$d->attendance_day->dayOfWeek]}}</td>
                        <!-- 出社時間（hour） -->
                          <td>zz</td>
                        <!-- 出社時間（minitus）-->
                          <td>zz</td>
                        <!-- 退社時間（hour）-->
                          <td>zz</td>
                        <!-- 退社時間（minitus）-->
                          <td>zz</td>
                        <!-- 在社時間 -->
                          <td>zz</td>
                        <!-- 備考 -->
                          <td>zz</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

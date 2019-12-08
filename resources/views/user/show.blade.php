@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-16">
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
                          指定勤務開始時間：{{$user->designate_start_time}}<br>
                          指定勤務終了時間：{{$user->designate_end_time}}
                          </th>
                        <th colspan="3">基本時間：{{$user->basic_work_time}}</th>
                        <th>初日：{{$firstDay->format('m/d')}}</th>
                        </tr>

                        <tr>
                        <th>所属：{{$user->belong}}</th>
                        <th>氏名：{{$user->name}}</th>
                          <th>コード</th>
                          <th>2222</th>
                          <th>
                            出勤日数：
                            @if ($attendanceDays > 0)
                              {{$attendanceDays}}日
                            @else
                              0日
                            @endif
                          </th>
                        <th>締め：{{$lastDay->format('m/d')}}</th>
                        </tr>
                      </thead>
                    </table>

                    <div class="btn-group">
                      <a href="/attendance/edit?current_day={{$currentDay}}" class="btn btn-primary">勤怠編集</a>
                      <a href="" class="btn btn-primary">CSV出力</a>
                      <a href="" class="btn btn-primary">勤怠ログ（承認済み）</a>
                    </div>

                    <table class="table table-bordered table-striped table-condensed">
                      <thead>
                        <tr>
                          <th rowspan="2">残業申請</th>
                          <th rowspan="2">日付</th>
                          <th rowspan="2">曜日</th>
                          <th colspan="3">出社</th>
                          <th colspan="3">退社</th>
                          <th rowspan="2">在社時間</th>
                          <th rowspan="2">備考</th>
                          <th colspan="2">終了予定時間</th>
                          <th rowspan="2">時間外時間</th>
                          <th rowspan="2">業務処理内容</th>
                          <th rowspan="2">指示者確認</th>
                        </tr>
                        <tr>
                          <th>時</th>
                          <th>分</th>
                          <th></th>
                          <th>時</th>
                          <th>分</th>
                          <th></th>
                          <th>時</th>
                          <th>分</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($date as $d)
                        <tr>
                        <!-- 残業申請 -->
                          <td><a href="#" class="btn btn-primary">残業申請</a></td>
                        <!-- 日付 -->
                          <td>{{$d->attendance_day->format('m/d')}}</td>
                        <!-- 曜日 -->
                          <td>{{$week[$d->attendance_day->dayOfWeek]}}</td>
                        <!-- 出社時間（hour） -->
                          <td>
                            @if ($d->start_time !== null)
                              {{$d->start_time->format('H')}}
                            @endif
                          </td>
                        <!-- 出社時間（minitus）-->
                          <td>
                            @if ($d->start_time !== null)
                              {{$d->start_time->format('i')}}
                            @endif
                          </td>
                        <!-- 出社ボタン -->
                          <td>
                            @if ($today == $d->attendance_day && $d->start_time === null)
                              <form action="{{route('start_time')}}" method="post">
                                @csrf
                                <input type="submit" value="出社" class="btn btn-primary">
                              </form>
                            @endif
                          </td>
                        <!-- 退社時間（hour）-->
                          <td>
                            @if ($d->end_time !== null)
                              {{$d->end_time->format('H')}}
                            @endif
                          </td>
                        <!-- 退社時間（minitus）-->
                          <td>
                            @if ($d->end_time !== null)
                              {{$d->end_time->format('i')}}
                            @endif
                          </td>
                        <!-- 退社ボタン -->
                          <td>
                            @if ($today == $d->attendance_day && $d->start_time !== null && $d->end_time === null)
                              <form action="{{route('end_time')}}" method="post">
                                @csrf
                                <input type="submit" value="退社" class="btn btn-primary">
                              </form>
                            @endif
                          </td>
                        <!-- 在社時間 -->
                          <td>
                            @if ($d->start_time !== null && $d->end_time !== null)
                                {{calculation($d->start_time->diffInSeconds($d->end_time))}}
                            @endif
                          </td>
                        <!-- 備考 -->
                          <td>{{$d->note}}</td>
                        <!-- 終了予定時間（hours）-->
                          <td></td>
                        <!-- 終了予定時間（minites）-->
                          <td></td>
                        <!-- 時間外時間 -->
                          <td></td>
                        <!-- 業務処理内容-->
                          <td></td>
                        <!-- 指示者確認 -->
                          <td></td>
                        </tr>
                        @endforeach
                        <td colspan="2">
                          総合勤務時間：
                        </td>
                        <td colspan="6"></td>
                        <td></td>
                        <td>在社時間の合計：
                          @if ($totalWorkingTime > 0)
                            {{$totalWorkingTime}}
                          @else
                            0.00
                          @endif
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>テスト</td>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

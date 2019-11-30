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
                          <th>出勤日数：
                            0日
                          </th>
                        <th>締め：{{$lastDay->format('m/d')}}</th>
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
                          <th colspan="3">出社</th>
                          <th colspan="3">退社</th>
                          <th rowspan="2">在社時間</th>
                          <th rowspan="2">備考</th>
                        </tr>
                        <tr>
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
                                {{$d->start_time->diffInSeconds($d->end_time)}}
                            @endif
                          </td>
                        <!-- 備考 -->
                          <td></td>
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

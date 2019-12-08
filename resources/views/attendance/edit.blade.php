@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <h2>勤怠編集</h2>
                  @if (count($errors) > 0)
                  <div class="errors">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                      @endforeach
                    </ul>
                  @endif
                  </div>
                  <form action="{{route('attendance_update')}}" method="post">
                    @csrf
                    <input type="hidden" name="current_day" value="{{$currentDay}}">
                    <table class="table table-bordered table-striped table-condensed">
                      <thead>
                        <tr>
                          <th>日付</th>
                          <th>曜日</th>
                          <th>出社</th>
                          <th>退社</th>
                          <th>翌日</th>
                          <th>在社時間</th>
                          <th>備考</th>
                          <th>指示者確認</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($date as $key => $d)
                        <tr>
                          <!-- 日付 -->
                          <td>{{$d->attendance_day->format('m/d')}}</td>
                          <!-- 曜日 -->
                          <td>{{$week[$d->attendance_day->dayOfWeek]}}</td>
                          <!-- 出社時間 -->
                          <td>
                            @if ($d->start_time === null)
                              <input type="time" name="attendance[{{$d->id}}][start_time]" value="" class="form-control" @if ($d->attendance_day > $today) readonly @endif>
                            @else
                              <input type="time" name="attendance[{{$d->id}}][start_time]" value="{{$d->start_time->format('H:i')}}" class="form-control" @if ($d->attendance_day > $today) readonly @endif>
                            @endif
                          </td>
                          <!-- 退社時間 -->
                          <td>
                            @if ($d->end_time === null)
                              <input type="time" name="attendance[{{$d->id}}][end_time]" value="" class="form-control" @if ($d->attendance_day > $today) readonly @endif>
                            @else
                              <input type="time" name="attendance[{{$d->id}}][end_time]" value="{{$d->end_time->format('H:i')}}" class="form-control" @if ($d->attendance_day > $today) readonly @endif>
                            @endif
                          </td>
                          <!-- 翌日 -->
                          <td></td>
                          <!-- 在社時間 -->
                          <td>
                            @if ($d->start_time !== null && $d->end_time !== null)
                              {{calculation($d->start_time->diffInSeconds($d->end_time))}}
                            @endif
                          </td>
                          <!-- 備考 -->
                          <td>
                            <input type="text" name="attendance[{{$d->id}}][note]" value="{{$d->note}}" class="form-control" @if ($d->attendance_day > $today) readonly @endif>
                          </td>
                          <!-- 指示者確認 -->
                          <td></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <button type="button" class="btn btn-primary" onclick="location.href='/show?current_day={{$currentDay}}'">キャンセル</button>
                    <input type="submit" class="btn btn-primary" value="更新する">
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <h2>勤怠編集</h2>
                  <form action="{{route('attendance_update')}}" method="post">
                    @csrf
                    <table class="table table-bordered table-striped table-condensed">
                      <thead>
                        <tr>
                          <th>日付</th>
                          <th>曜日</th>
                          <th>出社</th>
                          <th>退社</th>
                          <th>在社時間</th>
                          <th>備考</th>
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
                              <input type="time" name="start_time[{{$d->id}}]" value="" class="form-control">
                            @else
                              <input type="time" name="start_time[{{$d->id}}]" value="{{$d->start_time->format('H:i')}}" class="form-control">
                            @endif
                          </td>
                          <!-- 退社時間 -->
                          <td>
                            @if ($d->end_time === null)
                              <input type="time" name="end_time[{{$d->id}}]" value="" class="form-control">
                            @else
                              <input type="time" name="end_time[{{$d->id}}]" value="{{$d->end_time->format('H:i')}}" class="form-control">
                            @endif
                          </td>
                          <!-- 在社時間 -->
                          <td>
                            @if ($d->start_time !== null && $d->end_time !== null)
                              {{calculation($d->start_time->diffInSeconds($d->end_time))}}
                            @endif
                          </td>
                          <!-- 備考 -->
                          <td>
                            <input type="text" name="note[{{$d->id}}]" value="{{$d->note}}" class="form-control">
                          </td>
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

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <h2>勤怠編集</h2>
                  <form action="#" method="post">
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
                          <td>{{$d->attendance_day->format('m/d')}}</td>
                          <td>{{$week[$d->attendance_day->dayOfWeek]}}</td>
                          <td>
                            @if ($d->start_time === null)
                              <input type="time" name="start_time" value="" class="form-control">
                            @else
                              <input type="time" id="start_time" name="start_time" value="{{$d->start_time->format('H:i')}}" class="form-control">
                            @endif
                          </td>
                          <td>
                            @if ($d->end_time === null)
                              <input type="time" name="end_time" value="" class="form-control">
                            @else
                              <input type="time" name="end_time" value="{{$d->end_time->format('H:i')}}" class="form-control">
                            @endif
                          </td>
                          <td>
                            @if ($d->start_time !== null && $d->end_time !== null)
                              {{calculation($d->start_time->diffInSeconds($d->end_time))}}
                            @endif
                          </td>
                          <td>
                            {{$d->note}}
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

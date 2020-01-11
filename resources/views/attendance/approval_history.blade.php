@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <h2>勤怠ログ</h2>
                  @if (count($errors) > 0)
                  <div class="errors">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                      @endforeach
                    </ul>
                  </div>
                  @endif

                  <button type="reset" class="btn btn-default clearForm" id="reset">リセット</button>

                  <div class="input-group" id="year">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        年
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li id="2017" class="drop-list"><a>2017</a></li>
                        <li id="2018" class="drop-list"><a>2018</a></li>
                        <li id="2019" class="drop-list"><a>2019</a></li>
                        <li id="2020" class="drop-list"><a>2020</a></li>
                      </ul>
                    </span>
                    <div class="col-xs-2 px-0">
                      <input type="text" class="form-control" placeholder="xxx" readonly>
                    </div>
                  </div>

                  <div class="input-group" id="month">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        月
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                          <li id="01"><a>1</a></li>
                          <li id="02"><a>2</a></li>
                          <li id="03"><a>3</a></li>
                          <li id="04"><a>4</a></li>
                          <li id="05"><a>5</a></li>
                          <li id="06"><a>6</a></li>
                          <li id="07"><a>7</a></li>
                          <li id="08"><a>8</a></li>
                          <li id="09"><a>9</a></li>
                          <li id="10"><a>10</a></li>
                          <li id="11"><a>11</a></li>
                          <li id="12"><a>12</a></li>
                      </ul>
                    </span>
                    <div class="col-xs-2 px-0">
                      <input type="text" class="form-control" placeholder="yyy" readonly>
                    </div>
                  </div>
                  <table class="table table-bordered table-striped table-condensed">
                    <thead>
                      <tr>
                        <th>日付</th>
                        <th>変更前出社時間</th>
                        <th>変更前退社時間</th>
                        <th>変更後出社時間</th>
                        <th>変更後退社時間</th>
                        <th>指示者</th>
                        <th>承認日</th>
                      </tr>
                    </thead>
                    @foreach ($approvalData as $val)
                    <tbody>
                      <tr>
                        <!-- 第一項：日付 -->
                        <td>{{$val->attendance_day}}</td>
                        <!-- 第二項：変更前出社時間 -->
                        <td>
                          @if ($val->previous_start_time)
                          {{$val->previous_start_time->format('H:i')}}
                          @endif
                        </td>
                        <!-- 第三項：変更前退社時間 -->
                        <td>
                          @if ($val->previous_end_time)
                          {{$val->previous_end_time->format('H:i')}}
                          @endif
                        </td>
                        <!-- 第四項：変更後出社時間 -->
                        <td>
                          @if ($val->start_time)
                          {{$val->start_time->format('H:i')}}
                          @endif
                        </td>
                        <!-- 第五項：変更後退社時間 -->
                        <td>
                          @if ($val->end_time)
                          {{$val->end_time->format('H:i')}}
                          @endif
                        </td>
                        <!-- 第六項：指示者確認 -->
                        <td>{{$val->attendance_instructor}}</td>
                        <!-- 第七項：承認日 -->
                        <td>{{$val->approval_date}}</td>
                      </tr>
                    </tbody>
                    @endforeach
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  $(function() {
    year = moment().format('YYYY');
    month = moment().format('MM');

    $('#year li').on('click', function() {
      year = $(this).attr('id');
      $.ajax({
        url: "{{ action('AttendanceController@approval_history') }}",
        type: 'GET',
        data: {
          year: year,
          month: month
        },
        dataType: 'json'
      })
      .done(function(data) {
        $('tbody').find('tr').remove();
        $('tbody').find('td').remove();
        $(data).each(function(i, val) {
          attendanceDay         = val.attendance_day;
          previousStartTime     = moment(val.previous_start_time).format('HH:mm');
          previousEndTime       = moment(val.previous_end_time).format('HH:mm');
          startTime             = moment(val.start_time).format('HH:mm');
          endTime               = moment(val.end_time).format('HH:mm');
          attendanceInstructor  = val.attendance_instructor;
          approvalDate          = val.approval_date;
          $('tbody').append(
            $('<tr>')
            .append('<td>' + attendanceDay + '</td>')
            .append('<td>' + previousStartTime + '</td>')
            .append('<td>' + previousEndTime + '</td>')
            .append('<td>' + startTime + '</td>')
            .append('<td>' + endTime + '</td>')
            .append('<td>' + attendanceInstructor + '</td>')
            .append('<td>' + approvalDate + '</td>')
            .append('</tr>')
          )
        })
      })
      .fail(function(data) {
        alert(data.responseJSON);
      })
    });
  });
</script>
@endsection

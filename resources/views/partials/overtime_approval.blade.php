<!-- モーダル・残業承認 -->
<div class="modal fade" id="aa" tabindex="-1">
  <div class="modal-dialog modal-dialog-r">
    <div class="modal-content">
      <div class="modal-body">
        <form action="{{route('overtime_approval')}}" method="post">
          @csrf
          <input type="hidden" name="current_day" value="{{$currentDay}}">
          @foreach ($approvalOverwork as $val)
          <div class="modal-header modal-header-r">
            <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            <h4 class="modal-title">{{$val->user->name}}からの残業申請</h4>
          </div>
          <table class="txt1 table table-bordered table-striped table-condensed">
            <thead>
              <tr>
                <th>日付</th>
                <th>曜日</th>
                <th>終了予定時間</th>
                <th>指定勤務終了時間</th>
                <th>時間外時間</th>
                <th>業務処理内容</th>
                <th>指示者確認</th>
                <th>変更</th>
                <th>勤怠確認</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <!-- 日付 -->
                <td>{{$val->attendance_day->format('m/d')}}</td>
                <!-- 曜日 -->
                <td>{{$week[$val->attendance_day->dayOfWeek]}}</td>
                <!-- 終了予定時間 -->
                <td>{{$val->scheduled_end_time->format('H:i')}}</td>
                <!-- 指定勤務終了時間 -->
                <td>{{$val->designate_end_time->format('H:i')}}</td>
                <!-- 時間外時間 -->
                <td>{{overtimeCalculation($val->is_o_next_day, $val->scheduled_end_time, $val->designate_end_time)}}</td>
                <!-- 業務処理内容 -->
                <td>{{$val->business_description}}</td>
                <!-- 指示者確認 -->
                <td>
                  <select name="overwork[{{$val->id}}][apply_overtime_status]" class="form-control">
                    @foreach (config('const.APPLY_OVERTIME_STATUS') as $key => $value)
                      <option value="{{$key}}" @if ($key == $val->apply_overtime_status) selected @endif>{{$value}}</option>
                    @endforeach
                  </select>
                </td>
                <!-- 変更 -->
                <td>
                  <input type="hidden" name="overwork[{{$val->id}}][change]" value="0" class="form-control">
                  <input type="checkbox" name="overwork[{{$val->id}}][change]" value="1" class="form-control">
                </td>
                <!-- 勤怠確認 -->
                <td>
                  <a href="/show?current_day={{$val->attendance_day->format('Y-m-d')}}&user_id={{$val->user_id}}" class="btn btn-primary">勤怠確認</a>
                </td>
              </tr>
            </tbody>
          </table>
          @endforeach
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
            <input type="submit" class="btn btn-primary" value="申請する">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
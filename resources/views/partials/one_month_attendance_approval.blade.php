<!-- モーダル・月勤怠承認 -->
<div class="modal fade" id="bb" tabindex="-1">
  <div class="modal-dialog modal-dialog-r">
    <div class="modal-content">
      <div class="modal-body">
        <form action="{{route('one_month_attendance_approval')}}" method="post">
          @csrf
          <input type="hidden" name="current_day" value="{{$currentDay}}">
          @foreach ($approvalOneMonthAttendance as $val)
          <div class="modal-header modal-header-r">
            <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            <h4 class="modal-title">{{$val->user->name}}からの月勤怠申請</h4>
          </div>
          <table class="txt1 table table-bordered table-striped table-condensed">
            <thead>
              <tr>
                <th>月</th>
                <th>指示者確認</th>
                <th>変更</th>
                <th>勤怠を確認</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <!-- 月 -->
                <td>{{$val->target_month->format('m')}}月</td>
                <!-- 指示者確認 -->
                <td>
                  <select name="one_month_attendance[{{$val->id}}][apply_status]" class="form-control">
                    @foreach (config('const.APPLY_OVERTIME_STATUS') as $key => $value)
                      <option value="{{$key}}" @if ($key == $val->apply_status) selected @endif>{{$value}}</option>
                    @endforeach
                  </select>
                </td>
                <!-- 変更 -->
                <td>
                  <input type="hidden" name="one_month_attendance[{{$val->id}}][change]" value="0" class="form-control">
                  <input type="checkbox" name="one_month_attendance[{{$val->id}}][change]" value="1" class="form-control">
                </td>
                <!-- 勤怠確認 -->
                <td>
                  <a href="/show?current_day={{$val->target_month->format('Y-m-d')}}&user_id={{$val->user_id}}" class="btn btn-primary">勤怠確認</a>
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
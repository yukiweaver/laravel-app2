<!-- モーダル・残業申請 -->
<div class="modal fade" id="a{{$d->id}}" tabindex="-1">
  <div class="modal-dialog modal-dialog-r">
    <div class="modal-content">
      <div class="modal-header modal-header-r">
        <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
        <h4 class="modal-title">残業申請</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('overtime')}}" method="post">
          @csrf
          <input type="hidden" name="attendance_id" value="{{$d->id}}">
          <table class="txt1 table table-bordered table-striped table-condensed">
            <thead>
              <tr>
                <th>日付</th>
                <th>曜日</th>
                <th>終了予定時間（必須）</th>
                <th>翌日</th>
                <th>業務処理内容</th>
                <th>指示者確認（必須）</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <!-- 日付 -->
                <td>{{$d->attendance_day->format('m/d')}}</td>
                <!-- 曜日 -->
                <td>{{$week[$d->attendance_day->dayOfWeek]}}</td>
                <!-- 終了予定時間 -->
                <td>
                  @if ($d->scheduled_end_time == null)
                  <input type="time" name="scheduled_end_time" value="" class="form-control">
                  @else
                  <input type="time" name="scheduled_end_time" value="{{$d->scheduled_end_time->format('H:i')}}" class="form-control">
                  @endif
                </td>
                <!-- 翌日 -->
                <td>
                  <input type="hidden" name="is_next_day" value="0">
                  <input type="checkbox" name="is_next_day" value="1" @if ($d->is_o_next_day) checked @endif class="form-control">
                </td>
                <!-- 業務処理内容 -->
                <td>
                  <input type="text" name="business_description" value="{{$d->business_description}}" class="form-control">
                </td>
                <!-- 指示者確認 -->
                <td>
                  <select name="instructor_id" class="form-control">
                    <option value="">選択してください</option>
                    @foreach ($superiors as $superior)
                      <option value="{{$superior->id}}">{{$superior->name}}</option>
                    @endforeach
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
            <input type="submit" class="btn btn-primary" value="申請する">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
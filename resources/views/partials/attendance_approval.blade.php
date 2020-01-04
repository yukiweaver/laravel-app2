<!-- モーダル・勤怠変更承認 -->
<div class="modal fade" id="aaa" tabindex="-1">
  <div class="modal-dialog modal-dialog-r">
    <div class="modal-content">
      <div class="modal-body">
        <form action="#" method="post">
          @csrf
          {{-- <input type="hidden" name="attendance_id" value="{{$d->id}}"> --}}
          <div class="modal-header modal-header-r">
            <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            <h4 class="modal-title">##からの勤怠申請</h4>
          </div>
          <table class="txt1 table table-bordered table-striped table-condensed">
            <thead>
            <tr>
              <th rowspan="3">日付</th>
              <th rowspan="3">曜日</th>
              <th colspan="4">変更前</th>
              <th colspan="4">変更後</th>
              <th rowspan="3">備考</th>
              <th rowspan="3">指示者確認</th>
              <th rowspan="3">変更</th>
              <th rowspan="3">勤怠を確認</th>
            </tr>
            <tr>
              <th colspan="2">出社</th>
              <th colspan="2">退社</th>
              <th colspan="2">出社</th>
              <th colspan="2">退社</th>
            </tr>
            <tr>
              <th>時</th>
              <th>分</th>
              <th>時</th>
              <th>分</th>
              <th>時</th>
              <th>分</th>
              <th>時</th>
              <th>分</th>
            </tr>
            </thead>
      
            <tbody>
              <tr>
                <!-- 第一項：日付 -->
                <td></td>
                <!-- 第二項：曜日 -->
                <td></td>
                <!-- 第三項：変更前　出社　時 -->
                <td></td>
                <!-- 第四項：変更前　出社　分 -->
                <td></td>
                <!-- 第五項：変更前　退社　時 -->
                <td></td>
                <!-- 第六項：変更前　退社　分 -->
                <td></td>
                <!-- 第七項：変更後　出社　時 -->
                <td></td>
                <!-- 第八項：変更後　出社　分 -->
                <td></td>
                <!-- 第九項：変更後　退社　時 -->
                <td></td>
                <!-- 第十項：変更後　退社　分 -->
                <td></td>
                <!-- 第十一項：備考 -->
                <td></td>
                <!-- 第十二項：指示者確認 -->
                <td></td>
                <!-- 第十三項：変更 -->
                <td></td>
                <!-- 第十四項：勤怠を確認 -->
                <td></td>
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
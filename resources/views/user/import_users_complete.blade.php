@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <h2>CSVインポート - 完了</h2>
                  <div class="csv_import">
                    @if (count($importErrors) > 0)
                    <div class="errors">
                      <h4>以下のエラーが発生しました。登録処理は全て無効です。</h4>
                      <ul>
                        @foreach ($importErrors as $line => $val)
                          @foreach ($val as $error)
                            <li>{{$line}}行目：{{$error}}</li>
                          @endforeach
                        @endforeach
                      </ul>
                    </div>
                    @else
                      @if ($isResult)
                        <h4>登録が完了しました。</h4>
                      @else
                        <div class="errors">
                          <h4>DBエラーが発生しました。登録処理は全て無効です。</h4>
                        </div>
                      @endif
                    @endif
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

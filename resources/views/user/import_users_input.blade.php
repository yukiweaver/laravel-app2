@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <h2>CSVインポート - 入力</h2>
                  @if (count($errors) > 0)
                  <div class="errors">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                      @endforeach
                    </ul>
                  </div>
                  @endif
                  <div class="csv_import">
                    <form action="{{ route('import_users_complete') }}" method="post">
                      @csrf
                      <input type="file" name="csv_file" class="form-control">
                      <input type="submit" class="btn btn-primary" value="CSVインポート">
                    </form>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

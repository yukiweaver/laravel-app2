@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <h2>ユーザ一覧</h2>
                  @if (count($errors) > 0)
                  <div class="errors">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                      @endforeach
                    </ul>
                  </div>
                  @endif
                  <ul class="users">
                    @foreach ($users as $user)
                      <li>
                        {{$user->name}}
                        <div class="user-edit">
                        | <button type="button" data-toggle="collapse" data-target="#a{{$user->id}}" aria-expanded="false" aria-controls="collapseExample" class="btn-sm btn-primary">編集</button>
                        </div>
                        <div id="a{{$user->id}}" class="collapse">
                          <form action="{{route('admin_update')}}" method="post">
                            @csrf
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <label for="name">名前</label>
                            <input type="text" name="name" value="{{$user->name}}" id="name" class="form-control">

                            <label for="email">メールアドレス</label>
                            <input type="email" name="email" value="{{$user->email}}" id="email" class="form-control">

                            <label for="belong">所属</label>
                            <input type="text" name="belong" value="{{$user->belong}}" id="belong" class="form-control">

                            <label for="number">社員番号</label>
                            <input type="text" name="number" value="{{$user->number}}" id="number" class="form-control">

                            <label for="card_number">カード番号</label>
                            <input type="text" name="card_number" value="{{$user->card_number}}" id="card_number" class="form-control">

                            <label for="basic_work_time">基本勤務時間</label>
                            <input type="time" name="basic_work_time" value="{{$user->basic_work_time}}" id="basic_work_time" class="form-control">

                            <label for="designate_start_time">指定勤務開始時間</label>
                            <input type="time" name="designate_start_time" value="{{$user->designate_start_time}}" id="designate_start_time" class="form-control">

                            <label for="designate_end_time">指定勤務終了時間</label>
                            <input type="time" name="designate_end_time" value="{{$user->designate_end_time}}" id="designate_end_time" class="form-control"><br>

                            <input type="submit" value="　更　新　" class="btn btn-primary">
                          </form>
                        </div>
                      </li>
                    @endforeach
                  </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

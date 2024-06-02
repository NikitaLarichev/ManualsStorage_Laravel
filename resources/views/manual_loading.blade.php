@extends('layouts.app')
@section('menu')
@parent
@endsection
@section('content')

<div class="container">
<h6 class="mb-5">Здесь вы можете загрузить руководство по экплуатации в базу данных!</h6>
  @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
  @endif
<form method="post" enctype="multipart/form-data" action="{{route('loading')}}">
    @csrf
  <div class="form-group my-3">
    <input class="form-control w-25 h-auto" type="textarea" name="description" placeholder="Запишите сюда модель и марку прибора"/><br/>
    <input class="form-control-file my-2" type="file" id="file" name="file" multiple /><br/>
    <input type="hidden" name="author_email" value="{{Auth::user()->email}}">
  </div>
  <div>
    <button class="btn btn-primary">Отправить</button>
  </div>
</form>
</div>

@endsection
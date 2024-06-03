@extends('layouts.app')
@section('menu')
@parent
@endsection
@section('content')

<div class="container h-auto">
    <div class="container h-100 mb-5">
        {{$manual->manual_name}}
        <p>Загрузил: {{$manual->author_email}}</p>
        <p><a href="{{route('read',[$manual->manual_name])}}">Читать</a></p>
        <p><a href="{{route('download',[$manual->manual_name])}}">Скачать</a></p>
    </div>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container">
        @foreach($complaints as $complaint)
        <div class="my-3">
            <div class="bg-light my-1">{{$complaint->author_name}}</div>
            <div class="bg-light w-75 border h-auto my-1 rounded">{{$complaint->claim}}</div>
            @if(Auth::check())
                @if(Auth::user()->role == "admin")
                    <form method="post" action="{{route('complaint_delete')}}">
                    @csrf
                        <input type="hidden" name="complaint_id" value="{{$complaint->id}}"/>
                        <input class="btn btn-sm btn-danger" type="submit" name="submit" value="удалить"/>
                    </form>
                @endif
            @endif
        </div>
        @endforeach
    </div>
    <div class="container">
    @if(Auth::check())
        @if(Auth::user()->isBlocked == 0)
            <div class="form-group my-5">
                <form method="post" action="{{route('complaint')}}">
                @csrf
                    <input class="form-control w-75 h-auto" type="textarea" name="claim" placeholder="Жалобы и предложения к этой инструкции оставьте здесь)"/><br/>
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                    <input type="hidden" name="author_name" value="{{Auth::user()->name}}">
                    <input type="hidden" name="manual_id" value="{{$manual->id}}">
                    <input class="btn btn-primary" type="submit" name="submit" value="Отправить">
                </form>
            </div>
        @endif
    @endif
    </div>
</div>

@endsection
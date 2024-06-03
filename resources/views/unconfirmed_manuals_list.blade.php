@extends('layouts.app')
@section('menu')
@parent
@endsection
@section('content')

@if(Auth::check())
    @if(Auth::user()->role=="admin")
    <div class="container">
        <h5 class="my-4">Руководства ожидающие подтверждения</h5>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif       
        <table class="table table-striped table-light table-align-middle table-bordered">
            <thead>
                <tr>
                    <th>Описание (марка, модель)</th>
                    <th>Название файла</th>
                    <th>Емэйл автора</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            @foreach($uc_manuals as $uc_manual)               
                <tr>               
                    <form method="post" action={{route('confirm')}}>
                    @csrf
                    <td>
                        <input type="textarea" name="description" value="{{$uc_manual->description}}"/>
                    </td>
                    <td>
                        <a href="{{route('read',[$uc_manual->manual_name])}}">{{$uc_manual->manual_name}}</a>
                    </td>
                    <td>
                        <div>{{$uc_manual->author_email}}</div>
                    </td>
                    <td>
                        <input type="hidden" name="filename" value="{{$uc_manual->manual_name}}"/>
                        <input class="btn btn-sm btn-outline-success" type="submit" name="submit" value="подтвердить"/>
                    </td>
                    </form>
                    <td><form method="post" action={{route('delete_uc')}}>@csrf
                        <input type="hidden" name="filename" value="{{$uc_manual->manual_name}}"/>
                        <input class="btn btn-sm btn-outline-danger" type="submit" name="submit" value="удалить"/>
                    </form></td>
                </tr>
            @endforeach
        </table>
    </div>
    @endif
@endif

@endsection
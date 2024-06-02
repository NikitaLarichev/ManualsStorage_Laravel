@extends('layouts.app')
@section('menu')
@parent
@endsection
@section('content')

<div class="container">
    <form class="d-flex ms-3 me-5 mt-2 mb-3 w-25" method="get" action="{{route('search')}}">
        @csrf
        <input class="form-control me-1" type="text" placeholder="поиск" aria-label="Поиск" name="text"/>
        <button class="btn btn-outline-primary" type="submit" name="submit">Найти</button>
    </form>
    <h5>Руководства по эксплуатации:</h5>
    <table class="table table-striped table-info">
        @foreach($manuals as $manual)
            <tr>
                <td><div>{{$manual->description}}</div></td>
                <td><a href="read\{{$manual->manual_name}}">{{$manual->manual_name}}</a></td>
                <td><a href="download\{{$manual->manual_name}}">скачать</a></td>
                @if(Auth::check())
                    @if(Auth::user()->role == "admin")
                        <td><form method="post" action={{route('delete')}}>@csrf
                                <input type="hidden" name="filename" value="{{$manual->manual_name}}"/>
                                <input class="btn btn-sm btn-outline-danger" type="submit" name="submit" value="удалить"/>
                            </form>
                        </td>
                    @endif
                @endif
            </tr>
        @endforeach
    </table>
</div>

@endsection
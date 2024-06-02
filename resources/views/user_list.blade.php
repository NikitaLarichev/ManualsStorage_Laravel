@extends('layouts.app')
@section('menu')
@parent
@endsection
@section('content')

@if(Auth::check())
    @if(Auth::user()->role=="admin")
    <div class="container">
        <h5>Список пользователей</h5>
        <table class="table table-bordered mt-4">
                <tr>
                    <th>Id</th>
                    <th>Nickname</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            @foreach($users as $user)
                <tr class="{{$user->isBlocked == 1 ? 'table-danger' : 'table-success'}} border-light border-3">
                    <td >{{$user->id}}</td>
                    <td >{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        @if($user->role == "user")
                        <form method="post" action="{{route('blocking')}}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{$user->id}}"/>
                            <input class="btn btn-outline-dark btn-sm" type="submit" name="submit" 
                            @if($user->isBlocked == 1)
                                value="разблокировать"                           
                            @else
                                value="заблокировать"
                            @endif
                            />
                        </form>
                        @else
                        <div>admin</div>
                        @endif
                    </td>           
                </tr>
            @endforeach
        </table>
    </div>
    @endif
@endif

@endsection
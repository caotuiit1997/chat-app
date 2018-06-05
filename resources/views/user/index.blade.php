@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Enter message</div>

                <div class="panel-body">
                    @foreach($users as $user)
                   <div class="row">
                        <div class="col-md-8">{{$user->name}}</div>
                        <div class="col-md-4">
                            <form method="POST" action="createConversation">
                                {{csrf_field()}}
                                    <input type="hidden" name="user_1" value="{{Auth::user()->id}}">
                                    <input type="hidden" name="user_2" value="{{$user->_id}}">
                                    <button type="submit" class="btn btn-success">Chat</button>
                            </form>
                        </div>
                   </div>
                   @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

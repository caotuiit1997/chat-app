@extends('layouts.app')

@section('content')
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
<link rel="stylesheet" href="{{asset('css/style.css')}}">
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ ShowUserName::showUserNameFromId($participant_id) }}</div>

                <div class="panel-body">
                    <div class="message-box" id="message-box">
                        @foreach($messages as $message)
                        @if($message->sender_id == Auth::user()->id)
                            <div class="row message-row">
                                <div class="col-md-3 sender ">{{ ShowUserName::showUserNameFromId($message->sender_id)}} :</div>
                                <div class="col-md-6 sender-bubble">{{$message->message}}</div>
                            </div>
                        @else
                        <div class="row message-row">
                            <div class="col-md-6 col-md-offset-3 receiver-bubble">{{$message->message}}</div>
                            <div class="col-md-3 receiver">: {{ShowUserName::showUserNameFromId($message->sender_id)}}</div>
                        </div>
                        @endif
                        @endforeach
                        <div id="id"></div>
                    </div>
                    <div class="row">
                        <form>
                            {{csrf_field()}}
                            <div class="form-group">
                                <input type="text" id="message" class="form-control">
                            </div>
                            <input type="hidden" id="conver_id" value="{{ $conver_id }}">
                            <input type="hidden" id="sender_id" value="{{ Auth::user()->_id }}">
                            <input type="hidden" id="receiver_id" value="{{ $participant_id }}">
                            <button type="submit" id="send_message" class="btn btn-success">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var socket = io.connect('http://localhost:3000');
    socket.on('message', function (data) {
        var newData = JSON.parse(data);
        var sender_id = $('#sender_id').val();
        if (newData.sender_id == sender_id) {
            $( "#message-box" ).append(
                "<div class='row message-row' > <div class='col-md-3 sender '>"+newData.sender+"</div>"
                + "<div class='col-md-6 sender-bubble'>"+newData.message+"</div> </div>"
            );
        }else {
            $( "#message-box" ).append(
                "<div class='row message-row' > <div class='col-md-6 col-md-offset-3 receiver-bubble'>"+newData.message+"</div>"
                + "<div class='col-md-3 receiver'>"+newData.sender+"</div></div>"
            );
        }
      });
</script>
<script>
    $(document).ready(function(){
        $('#send_message').click(function(event){
            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').attr('value'),
                }
            }),
            $.ajax({
                url: '/sendmessage',
                method: 'POST',
                data: {
                    message: $('#message').val(),
                    sender: $('#sender_id').val(),
                    receiver: $('#receiver_id').val(),
                    conversation_id: $('#conver_id').val(),
                }
            })
        })
    })
</script>
@endsection
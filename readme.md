<h1>Build laravel chat application</h1>
<hr>
<b>Source has been inhanced, please follow the guideline below</b>
<br>

#My Operating system: Linux ubuntu 14.6

#Make sure you have all those requirements.
<br>
-Nodejs (v8.11.2)
<br>
-Composer
<br>
-Php version 7
<br>

#For the database, I use Mongodb for this project, but you can use mysql
<br>
<h2>Set up environment</h2>
<br>
#1: Install redis server
<br>
```
sudo apt-get install redis-server
```
<br>
#2: Install socket.io: please install version 1.7.4 for no more further conflict
<br>
```
npm install express redis socket.io:1.7.4
```
<br>
#3: Create file server.js to build a server
```
var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');

server.listen(8890);
io.on('connection', function (socket) {

  console.log("new client connected");
  var redisClient = redis.createClient();
  redisClient.subscribe('message');

  redisClient.on("message", function(channel, message) {
    console.log("mew message in queue "+ message + "channel");
    socket.emit(channel, message);
  });

  socket.on('disconnect', function() {
    redisClient.quit();
  });

});

```
<b>To run server: use</b>
```
node server.js
```

<h2>Laravel + Redis </h2>

#Install predis by composer
```
composer require predis/predis
```
# Change the alias name in config/app.php for no more further conflig
From
```
'Redis'    => 'Illuminate\Support\Facades\Redis',
```
To
```
'LRedis'    => 'Illuminate\Support\Facades\Redis',
```

<h2>laravel</h2>

#Create a controller name SocketController
```
php artisan make:controller socketController
```
#Edit code inside SocketController like this
```
<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Request;
use LRedis;

class SocketController extends Controller {

    public function index()
    {
        return view('socket');
    }

    public function writemessage()
    {
        return view('writemessage');
    }

    public function sendMessage(){
        $redis = LRedis::connection();
        $redis->publish('message', Request::input('message'));
        return redirect('writemessage');
    }
}
```

#Create a view for type message writemessage.blade.php
```
@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Send message</div>
                    <form action="sendmessage" method="POST">
                        <input type="text" name="message" >
                        <input type="submit" value="send">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
```

#Create a view for display message: socket.blade.php

```
@extends('app')

@section('content')
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>

    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2" >
              <div id="messages" ></div>
            </div>
        </div>
    </div>
    <script>
        var socket = io.connect('http://localhost:8890');
        socket.on('message', function (data) {
            $( "#messages" ).append( "<p>"+data+"</p>" );
          });
    </script>

@endsection
```

#Add those line in route/web.php
```
Route::get('socket', 'SocketController@index');
Route::post('sendmessage', 'SocketController@sendMessage');
Route::get('writemessage', 'SocketController@writemessage');
```
<h1><b>To run and test</b></h1>

#Run node server: node server.js
<br>
#Run laravel project: php artisan serve
<br>
#Open two browser: one is open localhost/writemessage and one is open localhost/socket
<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LRedis;
use App\Messages;
use App\Helpers\showUserName;
use Auth;
use App\Conversation;
class MessagesController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest');
    }

    public function index($participant_id)
    {
        $user_id = Auth::user()->_id;
        $coversation = Conversation::checkIfConversationExist($user_id, $participant_id);
        $conver_id = $coversation[0]->_id;
        $messages = Messages::all()->where('conversation_id', $conver_id);
        return view('messages.show', ['messages' => $messages, 'conver_id' => $conver_id, 'participant_id' => $participant_id]);
    }

    public function writemessage()
    {
        return view('messages.index');
    }

    public function sendMessage(Request $request){
        $redis = LRedis::connection();
        $messages = new Messages();
        $messages->message = $request->message;
        $messages->sender_id = $request->sender;
        $messages->receiver_id = $request->receiver;
        $messages->conversation_id = $request->conversation_id;

        $sender = showUserName::showUserNameFromId($messages->sender_id);
        $response_data = [
            'message' => $request->message,
            'sender' => $sender,
            'sender_id' => $messages->sender_id
        ];
        if($messages->save()) {
            $redis->publish('message', json_encode($response_data));
        }
    }
}

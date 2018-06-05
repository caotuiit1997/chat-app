<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use Auth;
class ConversationController extends Controller
{
    public function createConversation(Request $request) {
        $conver = new Conversation();
        $exist_conver = $conver->checkIfConversationExist($request->user_1, $request->user_2);

        if (count($exist_conver) == 0) {
            $conver->participant_id = $request->user_1. '-'. $request->user_2;
            $conver->user_1 = $request->user_1;
            $conver->user_2 = $request->user_2;
            $conver->save();
            return redirect('chat/'.$conver->user_2);
        }else {
            if (Auth::user()->_id == $exist_conver[0]->user_1){
                return redirect('chat/'.$exist_conver[0]->user_2);
            }else {
                return redirect('chat/'.$exist_conver[0]->user_1);
            }
        }
    }
}

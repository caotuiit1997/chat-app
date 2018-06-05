<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Conversation extends Model
{
    public static function checkIfConversationExist($user_1, $user_2){
        $case_1 = $user_1 . '-' . $user_2;
        $case_2 = $user_2 . '-' . $user_1;
        $exist_conver = Conversation::where('participant_id', $case_1)->orWhere('participant_id', $case_2)->get();
        return $exist_conver;
    }
}

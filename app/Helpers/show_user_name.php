<?php
namespace App\Helpers;
use App\User;

class showUserName {
    public static function showUserNameFromId($id){
        $user = User::find($id);
        return $user->name;
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
class UsersController extends Controller
{
    public function index() {
        $users = User::all()->where('_id','<>', Auth::user()->id);
        return view('user.index', ['users' => $users]);
    }
}

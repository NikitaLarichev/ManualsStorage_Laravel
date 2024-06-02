<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UnconfirmedManual;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return view('user_list', ['users'=>$users]);
    }

    public function toBlock(Request $request){
        $user_id = $request->input('user_id');
        $user_to_block = User::firstWhere('id', "$user_id");
        $user_to_block->isBlocked = $user_to_block->isBlocked == 0 ? 1 : 0;
        $user_to_block->save();
        return redirect("/user_list");
    }
}

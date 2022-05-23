<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if(auth(guard: 'api')->user()->is_admin == TRUE){
            $users = User::all();
            return view('home-adm', compact('users'));
        } else {
            $user = auth(guard: 'api')->user();
            return view('home-user', compact('user'));
        }
    }
}

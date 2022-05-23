<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        if (auth(guard: 'api')->user()->category_license == TRUE){
            return view('category');
        } else {
            return view('error');
        }
    }
}

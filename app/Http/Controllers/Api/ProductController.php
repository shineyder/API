<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        if (auth(guard: 'api')->user()->product_license == TRUE){
            return view('product');
        } else {
            return view('error');
        }
    }
}

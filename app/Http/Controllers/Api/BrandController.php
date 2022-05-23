<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        if (auth(guard: 'api')->user()->brand_license == TRUE){
            return view('brand');
        } else {
            return view('error');
        }
    }
}

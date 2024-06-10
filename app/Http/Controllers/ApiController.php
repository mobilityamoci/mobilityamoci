<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class  ApiController extends Controller
{
    public function authenticate(Request $request)
    {
        dd($request->all());
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


     public function phone_auth()
    {
        return view('home');
    }
}

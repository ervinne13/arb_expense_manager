<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppSPAController extends Controller
{
    public function index()
    {
        return view('app');
    }
}

<?php

namespace App\Http\Controllers;

class MainController extends Controller
{
    public function welcome()
    {
        return view('pages.welcome');
    }
}

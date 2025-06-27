<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class MainController extends Controller 
{
    public function welcome()
    {
      return view('pages.welcome');
    }
}

<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function underConstruction()
    {
        return view('under-construction');
    }
}

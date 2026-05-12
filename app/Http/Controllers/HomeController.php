<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->studentProfile;

        return view('home', compact('profile'));
    }

    public function underConstruction()
    {
        return view('under-construction');
    }
}

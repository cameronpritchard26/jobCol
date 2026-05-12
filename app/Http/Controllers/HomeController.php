<?php

namespace App\Http\Controllers;

use App\Enums\AccountType;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $profile = match ($user->account_type) {
            AccountType::Student => $user->studentProfile,
            AccountType::Employer => $user->employerProfile,
            default => null,
        };

        return view('home', compact('profile'));
    }

    public function underConstruction()
    {
        return view('under-construction');
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\AccountType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected function controller(): StudentProfileController|EmployerProfileController
    {
        return match (Auth::user()->account_type) {
            AccountType::Student => app(StudentProfileController::class),
            AccountType::Employer => app(EmployerProfileController::class),
            default => abort(403, 'Profile not available for this account type.'),
        };
    }

    public function show()
    {
        return $this->controller()->show();
    }

    public function create()
    {
        return $this->controller()->create();
    }

    public function store(Request $request)
    {
        return $this->controller()->store($request);
    }

    public function edit()
    {
        return $this->controller()->edit();
    }

    public function update(Request $request)
    {
        return $this->controller()->update($request);
    }
}

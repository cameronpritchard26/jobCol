@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-8rem)] px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow p-8">

        {{-- Step 1: Account type selection --}}
        <div id="type-selection" class="{{ old('account_type') ? 'hidden' : '' }}">
            <h2 class="text-2xl font-bold mb-2 text-center">Create Account</h2>
            <p class="text-sm text-gray-500 text-center mb-6">Choose your account type</p>

            <div class="space-y-3">
                <button onclick="selectType('student')" type="button"
                    class="w-full flex items-center gap-4 border-2 border-gray-200 rounded-xl px-5 py-4 hover:border-indigo-500 hover:bg-indigo-50 transition cursor-pointer">
                    <span class="text-3xl">🎓</span>
                    <div class="text-left">
                        <span class="block font-semibold text-gray-900">Student</span>
                        <span class="block text-sm text-gray-500">Search for jobs, connect with peers, build your network</span>
                    </div>
                </button>

                <button onclick="selectType('employer')" type="button"
                    class="w-full flex items-center gap-4 border-2 border-gray-200 rounded-xl px-5 py-4 hover:border-indigo-500 hover:bg-indigo-50 transition cursor-pointer">
                    <span class="text-3xl">🏢</span>
                    <div class="text-left">
                        <span class="block font-semibold text-gray-900">Employer</span>
                        <span class="block text-sm text-gray-500">Post jobs, review applications, find talent</span>
                    </div>
                </button>
            </div>

            <p class="text-sm text-center text-gray-600 mt-6">
                Already have an account?
                <a href="/" class="text-indigo-600 hover:underline font-medium">Sign in.</a>
            </p>
        </div>

        {{-- Step 2: Registration form --}}
        <div id="register-form" class="{{ old('account_type') ? '' : 'hidden' }}">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Create Account</h2>
                <span id="type-badge" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-full px-3 py-1">
                    {{ old('account_type') === 'employer' ? '🏢 Employer' : '🎓 Student' }}
                </span>
            </div>

            <form method="POST" action="/register" class="space-y-4">
                @csrf
                <input type="hidden" name="account_type" id="account_type" value="{{ old('account_type', '') }}">

                <div>
                    <label for="username" class="block text-sm font-medium mb-1">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('username')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white rounded-lg py-2 font-semibold hover:bg-indigo-700 transition cursor-pointer">
                    Register
                </button>
            </form>

            <button onclick="goBack()" type="button"
                class="w-full text-sm text-gray-500 hover:text-indigo-600 mt-4 transition cursor-pointer">
                ← Choose a different account type
            </button>
        </div>

    </div>
</div>

<script>
    function selectType(type) {
        document.getElementById('account_type').value = type;
        document.getElementById('type-selection').classList.add('hidden');
        document.getElementById('register-form').classList.remove('hidden');
        document.getElementById('type-badge').innerHTML = type === 'employer' ? '🏢 Employer' : '🎓 Student';
    }

    function goBack() {
        document.getElementById('account_type').value = '';
        document.getElementById('register-form').classList.add('hidden');
        document.getElementById('type-selection').classList.remove('hidden');
    }
</script>
@endsection

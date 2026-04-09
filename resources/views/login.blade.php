@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-8rem)] px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Sign In</h2>

        <form method="POST" action="/login" class="space-y-4">
            @csrf

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
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white rounded-lg py-2 font-semibold hover:bg-indigo-700 transition cursor-pointer">
                Sign In
            </button>
        </form>

        <p class="text-sm text-center text-gray-600 mt-6">
            Don't have an account?
            <a href="/register" class="text-indigo-600 hover:underline font-medium">Register here.</a>
        </p>
    </div>
</div>
@endsection

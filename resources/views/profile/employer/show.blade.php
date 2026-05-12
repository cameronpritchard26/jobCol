@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-8rem)] px-4 py-8">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow p-8">

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold">{{ $profile->name }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ Auth::user()->username }}</p>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-500">Industry</span>
                <span class="text-sm text-gray-900">{{ $profile->industry }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-500">Location</span>
                <span class="text-sm text-gray-900">{{ $profile->location }}</span>
            </div>

            @if ($profile->website)
                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-sm font-medium text-gray-500">Website</span>
                    <a href="{{ $profile->website }}" target="_blank" rel="noopener noreferrer"
                        class="text-sm text-indigo-600 hover:text-indigo-800 underline">{{ $profile->website }}</a>
                </div>
            @endif

            @if ($profile->description)
                <div class="pt-1">
                    <span class="text-sm font-medium text-gray-500 block mb-2">About</span>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $profile->description }}</p>
                </div>
            @endif
        </div>

        <a href="{{ route('profile.edit') }}"
            class="block text-center w-full bg-indigo-600 text-white rounded-lg py-2 font-semibold hover:bg-indigo-700 transition mt-6">
            Edit Profile
        </a>
    </div>
</div>
@endsection

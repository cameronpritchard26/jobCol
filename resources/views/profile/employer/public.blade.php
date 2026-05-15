@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-8rem)] px-4 py-8">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow p-8">

        <div class="text-center mb-6">
            <div class="flex justify-center mb-4">
                @if ($profile->profile_picture)
                    <img src="{{ asset('storage/profile-pictures/' . $profile->profile_picture) }}"
                        alt="Profile picture"
                        class="w-32 h-32 rounded-full object-cover border-2 border-gray-200">
                @else
                    <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                    </div>
                @endif
            </div>
            <h2 class="text-2xl font-bold">{{ $profile->name }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $profile->user->username }}</p>
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

    </div>
</div>
@endsection

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
            <h2 class="text-2xl font-bold">{{ $profile->first_name }} {{ $profile->last_name }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ Auth::user()->username }}</p>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-500">School</span>
                <span class="text-sm text-gray-900">{{ $profile->school }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-500">Major</span>
                <span class="text-sm text-gray-900">{{ $profile->major }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-500">Graduation Year</span>
                <span class="text-sm text-gray-900">{{ $profile->grad_year }}</span>
            </div>

            @if ($profile->about)
                <div class="pt-1">
                    <span class="text-sm font-medium text-gray-500 block mb-2">About Me</span>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $profile->about }}</p>
                </div>
            @endif
        </div>

        <a href="{{ route('student.profile.edit') }}"
            class="block text-center w-full bg-indigo-600 text-white rounded-lg py-2 font-semibold hover:bg-indigo-700 transition mt-6">
            Edit Profile
        </a>
    </div>
</div>
@endsection

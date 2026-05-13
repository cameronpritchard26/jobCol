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
            <h2 class="text-2xl font-bold">{{ $profile->first_name }} {{ $profile->last_name }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $profile->user->username }}</p>
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

        <div class="mt-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Education</h3>

            @if ($profile->educationEntries->isEmpty())
                <p class="text-sm text-gray-400">No education entries yet.</p>
            @else
                <div class="space-y-4">
                    @foreach ($profile->educationEntries as $entry)
                        <div class="border border-gray-100 rounded-lg p-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $entry->degree }}</p>
                            <p class="text-sm text-gray-600">{{ $entry->school }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $entry->start_year }} &ndash; {{ $entry->end_year }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Experience</h3>

            @if ($profile->experienceEntries->isEmpty())
                <p class="text-sm text-gray-400">No experience entries yet.</p>
            @else
                <div class="space-y-4">
                    @foreach ($profile->experienceEntries as $exp)
                        <div class="border border-gray-100 rounded-lg p-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $exp->title }}</p>
                            <p class="text-sm text-gray-600">{{ $exp->company }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ DateTime::createFromFormat('!m', $exp->start_month)->format('M') }} {{ $exp->start_year }}
                                &ndash;
                                @if ($exp->end_month && $exp->end_year)
                                    {{ DateTime::createFromFormat('!m', $exp->end_month)->format('M') }} {{ $exp->end_year }}
                                @else
                                    Present
                                @endif
                            </p>
                            @if ($exp->description)
                                <p class="text-sm text-gray-500 mt-2">{{ $exp->description }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

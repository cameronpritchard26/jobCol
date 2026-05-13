@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Search People</h1>

    <form method="GET" action="{{ route('network.index') }}" class="mb-6">
        <div class="flex gap-2">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search by name..."
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
            <input type="hidden" name="type" value="{{ $type }}">
            <button type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition cursor-pointer">
                Search
            </button>
        </div>
    </form>

    @if ($q)
        <div class="flex gap-2 mb-6">
            <a href="{{ route('network.index', ['q' => $q, 'type' => 'all']) }}"
                class="px-3 py-1.5 rounded-full text-sm font-medium {{ $type === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                All
            </a>
            <a href="{{ route('network.index', ['q' => $q, 'type' => 'students']) }}"
                class="px-3 py-1.5 rounded-full text-sm font-medium {{ $type === 'students' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                Students
            </a>
            <a href="{{ route('network.index', ['q' => $q, 'type' => 'employers']) }}"
                class="px-3 py-1.5 rounded-full text-sm font-medium {{ $type === 'employers' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">
                Employers
            </a>
        </div>

        @if ($students->isEmpty() && $employers->isEmpty())
            <p class="text-gray-500 text-sm">No results found for "{{ $q }}".</p>
        @endif

        @if ($students->isNotEmpty())
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Students</h2>
                <div class="space-y-3">
                    @foreach ($students as $student)
                        <a href="{{ route('profile.student.public', $student) }}"
                            class="flex items-center gap-4 bg-white rounded-xl shadow p-4 hover:shadow-md transition">
                            @if ($student->profile_picture)
                                <img src="{{ asset('storage/profile-pictures/' . $student->profile_picture) }}"
                                    alt="Profile picture"
                                    class="w-12 h-12 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                <p class="text-sm text-gray-500">{{ $student->major }} at {{ $student->school }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($employers->isNotEmpty())
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Employers</h2>
                <div class="space-y-3">
                    @foreach ($employers as $employer)
                        <a href="{{ route('profile.employer.public', $employer) }}"
                            class="flex items-center gap-4 bg-white rounded-xl shadow p-4 hover:shadow-md transition">
                            @if ($employer->profile_picture)
                                <img src="{{ asset('storage/profile-pictures/' . $employer->profile_picture) }}"
                                    alt="Profile picture"
                                    class="w-12 h-12 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $employer->name }}</p>
                                <p class="text-sm text-gray-500">{{ $employer->industry }}</p>
                                <p class="text-sm text-gray-400">{{ $employer->location }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <p class="text-gray-500 text-sm">Enter a name above to search for students and employers.</p>
    @endif
</div>
@endsection

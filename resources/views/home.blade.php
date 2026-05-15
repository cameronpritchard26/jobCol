@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="mb-8">
        @if ($profile)
            <h1 class="text-3xl font-bold text-gray-900">Welcome, {{ $profile->first_name ?? $profile->name }}!</h1>
        @else
            <h1 class="text-3xl font-bold text-gray-900">Welcome to JobCol!</h1>
        @endif
    </div>

    @if ($recentJobs !== null)
        {{-- Latest Jobs --}}
        <section class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Latest Jobs</h2>
                <a href="{{ route('jobs.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View all &rarr;</a>
            </div>

            @if ($recentJobs->isEmpty())
                <p class="text-gray-500 text-sm">No jobs posted yet.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($recentJobs as $job)
                        <div class="bg-white rounded-2xl shadow p-5 flex flex-col gap-2">
                            <a href="{{ route('jobs.show', $job) }}"
                                class="text-base font-semibold text-gray-900 hover:text-indigo-600 transition leading-tight">
                                {{ $job->title }}
                            </a>
                            <p class="text-sm text-gray-500">{{ $job->employer->name }}</p>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-medium bg-indigo-100 text-indigo-700 rounded-full px-2.5 py-1">
                                    {{ $job->job_type->label() }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $job->location }}</span>
                            </div>
                            @if ($job->salary)
                                <p class="text-sm text-gray-700">
                                    ${{ number_format($job->salary, 2) }}
                                    <span class="text-gray-500">/ {{ $job->salary_type->label() }}</span>
                                </p>
                            @endif
                            @if ($job->deadline)
                                <p class="text-xs text-gray-500">Due {{ $job->deadline->format('M d, Y') }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- People You May Know --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">People You May Know</h2>
                <a href="{{ route('network.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View all &rarr;</a>
            </div>

            @if ($suggestedStudents->isEmpty())
                <p class="text-gray-500 text-sm">No new people to connect with right now.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($suggestedStudents as $student)
                        <div class="bg-white rounded-2xl shadow p-5 flex flex-col items-center text-center gap-3">
                            <a href="{{ route('profile.student.public', $student) }}">
                                @if ($student->profile_picture)
                                    <img src="{{ asset('storage/profile-pictures/' . $student->profile_picture) }}"
                                        alt="{{ $student->first_name }}"
                                        class="w-16 h-16 rounded-full object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                            <div>
                                <a href="{{ route('profile.student.public', $student) }}"
                                    class="text-sm font-semibold text-gray-900 hover:text-indigo-600 transition">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </a>
                                @if ($student->major || $student->school)
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ implode(' at ', array_filter([$student->major, $student->school])) }}
                                    </p>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('connections.store', $student) }}">
                                @csrf
                                <button type="submit"
                                    class="text-xs font-semibold text-indigo-600 border border-indigo-600 rounded-full px-4 py-1 hover:bg-indigo-600 hover:text-white transition cursor-pointer">
                                    Connect
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

</div>
@endsection

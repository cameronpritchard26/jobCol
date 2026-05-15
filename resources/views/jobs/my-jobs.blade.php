@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-6 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-2xl font-bold text-gray-900 mb-6">My Jobs</h1>

    {{-- Tab bar --}}
    @php $tab = request('tab', 'saved'); @endphp
    <div class="flex border-b border-gray-200 mb-6">
        <a href="{{ route('student.my-jobs', ['tab' => 'saved']) }}"
            class="px-4 py-2 text-sm font-medium border-b-2 -mb-px transition
                {{ $tab === 'saved' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            Saved Jobs
            <span class="ml-1.5 text-xs bg-gray-100 text-gray-600 rounded-full px-2 py-0.5">{{ $savedJobs->count() }}</span>
        </a>
        <a href="{{ route('student.my-jobs', ['tab' => 'applied']) }}"
            class="px-4 py-2 text-sm font-medium border-b-2 -mb-px transition
                {{ $tab === 'applied' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            Applied Jobs
            <span class="ml-1.5 text-xs bg-gray-100 text-gray-600 rounded-full px-2 py-0.5">{{ $applications->count() }}</span>
        </a>
    </div>

    {{-- Saved tab --}}
    @if ($tab === 'saved')
        @forelse ($savedJobs as $saved)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-4 flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <a href="{{ route('jobs.show', $saved->job) }}"
                        class="text-base font-semibold text-gray-900 hover:text-indigo-600 transition">
                        {{ $saved->job->title }}
                    </a>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $saved->job->employer->name }}</p>
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        <span class="text-xs font-medium bg-indigo-100 text-indigo-700 rounded-full px-2.5 py-1">
                            {{ $saved->job->job_type->label() }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $saved->job->location }}</span>
                        @if ($saved->job->deadline)
                            <span class="text-xs text-gray-500">Deadline: {{ $saved->job->deadline->format('M d, Y') }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Saved {{ $saved->created_at->format('M d, Y') }}</p>
                </div>
                <form method="POST" action="{{ route('jobs.unsave', $saved->job) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-xs text-gray-500 hover:text-red-500 border border-gray-200 rounded-lg px-3 py-1.5 transition cursor-pointer whitespace-nowrap">
                        Unsave
                    </button>
                </form>
            </div>
        @empty
            <div class="text-center py-16 text-gray-400">
                <p class="text-sm">No saved jobs yet. Browse <a href="{{ route('jobs.index') }}" class="text-indigo-600 hover:underline">job listings</a> to save some.</p>
            </div>
        @endforelse

    {{-- Applied tab --}}
    @else
        @forelse ($applications as $application)
            @php
                $badgeClass = match($application->status) {
                    \App\Enums\ApplicationStatus::Submitted => 'bg-gray-100 text-gray-600',
                    \App\Enums\ApplicationStatus::Accepted  => 'bg-green-100 text-green-700',
                    \App\Enums\ApplicationStatus::Rejected  => 'bg-red-100 text-red-600',
                };
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-4 flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <a href="{{ route('jobs.show', $application->job) }}"
                        class="text-base font-semibold text-gray-900 hover:text-indigo-600 transition">
                        {{ $application->job->title }}
                    </a>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $application->job->employer->name }}</p>
                    <p class="text-xs text-gray-400 mt-2">Applied {{ $application->created_at->format('M d, Y') }}</p>
                </div>
                <span class="text-xs font-medium rounded-full px-2.5 py-1 {{ $badgeClass }} whitespace-nowrap">
                    {{ $application->status->label() }}
                </span>
            </div>
        @empty
            <div class="text-center py-16 text-gray-400">
                <p class="text-sm">No applications yet. Browse <a href="{{ route('jobs.index') }}" class="text-indigo-600 hover:underline">job listings</a> to apply.</p>
            </div>
        @endforelse
    @endif

</div>
@endsection

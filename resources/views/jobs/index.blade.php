@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $myEmployerProfileId ? 'My Job Postings' : 'Job Listings' }}</h1>
        @if ($myEmployerProfileId)
            <a href="{{ route('jobs.create') }}"
                class="bg-indigo-600 text-white rounded-lg px-4 py-2 text-sm font-semibold hover:bg-indigo-700 transition">
                Post a Job
            </a>
        @endif
    </div>

    @if ($jobPostings->isEmpty())
        <p class="text-gray-500 text-center py-16">No job listings yet.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($jobPostings as $job)
                <div class="bg-white rounded-2xl shadow p-6 flex flex-col justify-between">
                    <div>
                        <a href="{{ route('jobs.show', $job) }}"
                            class="text-lg font-semibold text-gray-900 hover:text-indigo-600 transition">
                            {{ $job->title }}
                        </a>
                        <p class="text-sm text-gray-500 mt-1">{{ $job->employer->name }}</p>

                        <div class="flex flex-wrap items-center gap-2 mt-3">
                            <span class="text-xs font-medium bg-indigo-100 text-indigo-700 rounded-full px-2.5 py-1">
                                {{ $job->job_type->label() }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $job->location }}</span>
                        </div>

                        <div class="mt-4 space-y-1">
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
                    </div>

                    @if ($myEmployerProfileId === $job->employer_id)
                        <div class="flex gap-2 mt-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('jobs.edit', $job) }}"
                                class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                            <form method="POST" action="{{ route('jobs.destroy', $job) }}"
                                onsubmit="return confirm('Delete this job posting?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium cursor-pointer">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $jobPostings->links() }}
        </div>
    @endif

</div>
@endsection

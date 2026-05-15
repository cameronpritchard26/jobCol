@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow p-8">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ $jobPosting->title }}</h1>
            <p class="text-gray-500 mt-1">
                <a href="{{ route('profile.employer.public', $jobPosting->employer) }}"
                    class="text-indigo-600 hover:text-indigo-800">
                    {{ $jobPosting->employer->name }}
                </a>
            </p>
        </div>

        <div class="space-y-3 mb-6">
            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-500">Location</span>
                <span class="text-sm text-gray-900">{{ $jobPosting->location }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-500">Job Type</span>
                <span class="text-xs font-medium bg-indigo-100 text-indigo-700 rounded-full px-2.5 py-1">
                    {{ $jobPosting->job_type->label() }}
                </span>
            </div>

            @if ($jobPosting->salary)
                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-sm font-medium text-gray-500">Salary</span>
                    <span class="text-sm text-gray-900">
                        ${{ number_format($jobPosting->salary, 2) }} / {{ $jobPosting->salary_type->label() }}
                    </span>
                </div>
            @endif

            @if ($jobPosting->deadline)
                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-sm font-medium text-gray-500">Application Deadline</span>
                    <span class="text-sm text-gray-900">{{ $jobPosting->deadline->format('M d, Y') }}</span>
                </div>
            @endif

            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-500">Posted</span>
                <span class="text-sm text-gray-900">{{ $jobPosting->created_at->format('M d, Y') }}</span>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-sm font-medium text-gray-500 mb-2">Description</h2>
            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $jobPosting->description }}</p>
        </div>

        @if ($isOwner)
            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('jobs.edit', $jobPosting) }}"
                    class="flex-1 text-center bg-indigo-600 text-white rounded-lg py-2 font-semibold hover:bg-indigo-700 transition text-sm">
                    Edit Posting
                </a>
                <form method="POST" action="{{ route('jobs.destroy', $jobPosting) }}"
                    onsubmit="return confirm('Delete this job posting?')" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full bg-red-50 text-red-600 border border-red-200 rounded-lg py-2 font-semibold hover:bg-red-100 transition text-sm cursor-pointer">
                        Delete Posting
                    </button>
                </form>
            </div>
        @endif

        <a href="{{ route('jobs.index') }}"
            class="block text-center text-sm text-gray-500 hover:text-gray-700 mt-4">
            ← Back to listings
        </a>
    </div>

</div>
@endsection

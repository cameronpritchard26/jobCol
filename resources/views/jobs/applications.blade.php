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

    <div class="mb-6">
        <a href="{{ route('jobs.show', $jobPosting) }}"
            class="text-sm text-gray-500 hover:text-gray-700">← Back to posting</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $jobPosting->title }}</h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $applications->count() }} {{ Str::plural('application', $applications->count()) }}
        </p>
    </div>

    @forelse ($applications as $application)
        @php
            $badgeClass = match($application->status) {
                \App\Enums\ApplicationStatus::Submitted => 'bg-gray-100 text-gray-600',
                \App\Enums\ApplicationStatus::Accepted  => 'bg-green-100 text-green-700',
                \App\Enums\ApplicationStatus::Rejected  => 'bg-red-100 text-red-600',
            };
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <a href="{{ route('profile.student.public', $application->student) }}"
                        class="text-base font-semibold text-gray-900 hover:text-indigo-600 transition">
                        {{ $application->student->first_name }} {{ $application->student->last_name }}
                    </a>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $application->student->school }} · {{ $application->student->major }}</p>
                    <p class="text-xs text-gray-400 mt-1">Applied {{ $application->created_at->format('M d, Y') }}</p>
                </div>
                <span class="text-xs font-medium rounded-full px-2.5 py-1 {{ $badgeClass }} whitespace-nowrap">
                    {{ $application->status->label() }}
                </span>
            </div>

            <div class="flex gap-2 mt-4 pt-4 border-t border-gray-100">
                @if ($application->status !== \App\Enums\ApplicationStatus::Accepted)
                    <form method="POST" action="{{ route('applications.update-status', $application) }}" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit"
                            class="w-full text-sm font-semibold rounded-lg px-4 py-2 transition cursor-pointer" style="background-color:#16a34a;color:#fff;">
                            Accept
                        </button>
                    </form>
                @endif

                @if ($application->status !== \App\Enums\ApplicationStatus::Rejected)
                    <form method="POST" action="{{ route('applications.update-status', $application) }}" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit"
                            class="w-full text-sm font-semibold rounded-lg px-4 py-2 transition cursor-pointer" style="background-color:#dc2626;color:#fff;">
                            Reject
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-16 text-gray-400">
            <p class="text-sm">No applications yet.</p>
        </div>
    @endforelse

</div>
@endsection

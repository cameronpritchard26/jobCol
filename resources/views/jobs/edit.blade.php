@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-8rem)] px-4 py-8">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Edit Job Posting</h2>

        <form method="POST" action="{{ route('jobs.update', $jobPosting) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium mb-1">Job Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $jobPosting->title) }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="location" class="block text-sm font-medium mb-1">Location</label>
                    <input id="location" name="location" type="text"
                        value="{{ old('location', $jobPosting->location) }}" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="job_type" class="block text-sm font-medium mb-1">Job Type</label>
                    <select id="job_type" name="job_type" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach (\App\Enums\JobType::cases() as $type)
                            <option value="{{ $type->value }}"
                                {{ old('job_type', $jobPosting->job_type->value) === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('job_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1">Description</label>
                <textarea id="description" name="description" rows="5" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ old('description', $jobPosting->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="salary" class="block text-sm font-medium mb-1">Salary</label>
                    <input id="salary" name="salary" type="number" step="0.01" min="0" required
                        value="{{ old('salary', $jobPosting->salary) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('salary')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="salary_type" class="block text-sm font-medium mb-1">Pay Period</label>
                    <select id="salary_type" name="salary_type" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="" disabled>Select period</option>
                        @foreach (\App\Enums\SalaryType::cases() as $type)
                            <option value="{{ $type->value }}"
                                {{ old('salary_type', $jobPosting->salary_type?->value) === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('salary_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="deadline" class="block text-sm font-medium mb-1">Application Deadline</label>
                <input id="deadline" name="deadline" type="date" required
                    value="{{ old('deadline', $jobPosting->deadline?->format('Y-m-d')) }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('deadline')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white rounded-lg py-2 font-semibold hover:bg-indigo-700 transition cursor-pointer">
                Save Changes
            </button>
        </form>

        <a href="{{ route('jobs.show', $jobPosting) }}"
            class="block text-center text-sm text-gray-500 hover:text-gray-700 mt-4">
            Cancel
        </a>
    </div>
</div>
@endsection

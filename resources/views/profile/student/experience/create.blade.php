@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-8rem)] px-4 py-8">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Add Experience</h2>

        <form method="POST" action="{{ route('experience.store') }}" class="space-y-4" id="experience-form">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium mb-1">Job Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="company" class="block text-sm font-medium mb-1">Company</label>
                <input id="company" name="company" type="text" value="{{ old('company') }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('company')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_month" class="block text-sm font-medium mb-1">Start Month</label>
                    <select id="start_month" name="start_month" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('start_month') == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                    @error('start_month')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="start_year" class="block text-sm font-medium mb-1">Start Year</label>
                    <input id="start_year" name="start_year" type="number" value="{{ old('start_year') }}" required
                        min="1900" max="2100"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('start_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" id="current_role" name="current_role"
                        {{ old('current_role') ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        onchange="toggleEndDate(this)">
                    <span class="text-sm font-medium">I currently work here</span>
                </label>
            </div>

            <div class="grid grid-cols-2 gap-4" id="end-date-fields">
                <div>
                    <label for="end_month" class="block text-sm font-medium mb-1">End Month</label>
                    <select id="end_month" name="end_month"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('end_month') == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                    @error('end_month')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_year" class="block text-sm font-medium mb-1">End Year</label>
                    <input id="end_year" name="end_year" type="number" value="{{ old('end_year') }}"
                        min="1900" max="2100"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('end_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1">Description <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea id="description" name="description" rows="3"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <a href="{{ route('profile.show') }}"
                    class="flex-1 text-center border border-gray-300 text-gray-700 rounded-lg py-2 font-semibold hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="flex-1 bg-indigo-600 text-white rounded-lg py-2 font-semibold hover:bg-indigo-700 transition cursor-pointer">
                    Add Experience
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleEndDate(checkbox) {
        const fields = document.getElementById('end-date-fields');
        const endMonth = document.getElementById('end_month');
        const endYear = document.getElementById('end_year');

        if (checkbox.checked) {
            fields.style.display = 'none';
            endMonth.value = '';
            endYear.value = '';
        } else {
            fields.style.display = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('current_role');
        if (checkbox.checked) {
            toggleEndDate(checkbox);
        }
    });
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-8rem)] px-4 py-8">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Edit Education</h2>

        <form method="POST" action="{{ route('education.update', $entry) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="degree" class="block text-sm font-medium mb-1">Degree</label>
                <input id="degree" name="degree" type="text" value="{{ old('degree', $entry->degree) }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('degree')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="school" class="block text-sm font-medium mb-1">School</label>
                <input id="school" name="school" type="text" value="{{ old('school', $entry->school) }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('school')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_year" class="block text-sm font-medium mb-1">Start Year</label>
                    <input id="start_year" name="start_year" type="number" value="{{ old('start_year', $entry->start_year) }}" required
                        min="1900" max="2100"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('start_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_year" class="block text-sm font-medium mb-1">End Year</label>
                    <input id="end_year" name="end_year" type="number" value="{{ old('end_year', $entry->end_year) }}" required
                        min="1900" max="2100"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('end_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('profile.show') }}"
                    class="flex-1 text-center border border-gray-300 text-gray-700 rounded-lg py-2 font-semibold hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="flex-1 bg-indigo-600 text-white rounded-lg py-2 font-semibold hover:bg-indigo-700 transition cursor-pointer">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

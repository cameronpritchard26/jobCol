@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-8rem)] px-4 py-8">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Edit Your Profile</h2>

        <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium mb-1">First Name</label>
                    <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $profile->first_name) }}" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('first_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium mb-1">Last Name</label>
                    <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $profile->last_name) }}" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('last_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="school" class="block text-sm font-medium mb-1">School</label>
                <input id="school" name="school" type="text" value="{{ old('school', $profile->school) }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('school')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="major" class="block text-sm font-medium mb-1">Major</label>
                    <input id="major" name="major" type="text" value="{{ old('major', $profile->major) }}" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('major')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="grad_year" class="block text-sm font-medium mb-1">Graduation Year</label>
                    <input id="grad_year" name="grad_year" type="number" value="{{ old('grad_year', $profile->grad_year) }}" required
                        min="1900" max="2100"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('grad_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="about" class="block text-sm font-medium mb-1">About Me <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea id="about" name="about" rows="4" maxlength="1000"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ old('about', $profile->about) }}</textarea>
                @error('about')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <a href="{{ route('student.profile.show') }}"
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

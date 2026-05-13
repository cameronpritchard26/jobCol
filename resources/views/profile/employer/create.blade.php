@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-8rem)] px-4 py-8">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Create Your Profile</h2>

        <form method="POST" action="{{ route('profile.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium mb-1">Company Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="industry" class="block text-sm font-medium mb-1">Industry</label>
                    <input id="industry" name="industry" type="text" value="{{ old('industry') }}" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('industry')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium mb-1">Location</label>
                    <input id="location" name="location" type="text" value="{{ old('location') }}" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="website" class="block text-sm font-medium mb-1">Website <span class="text-gray-400 font-normal">(optional)</span></label>
                <input id="website" name="website" type="url" value="{{ old('website') }}" placeholder="https://example.com"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('website')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1">About <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea id="description" name="description" rows="4" maxlength="1000"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white rounded-lg py-2 font-semibold hover:bg-indigo-700 transition cursor-pointer">
                Create Profile
            </button>
        </form>
    </div>
</div>
@endsection

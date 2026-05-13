<div class="flex flex-col items-center mb-6">
    <div class="relative group">
        @if ($profile->profile_picture)
            <img src="{{ asset('storage/profile-pictures/' . $profile->profile_picture) }}"
                alt="Profile picture"
                class="w-32 h-32 rounded-full object-cover border-2 border-gray-200">
        @else
            <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                </svg>
            </div>
        @endif
    </div>

    @error('profile_picture')
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror

    <div class="flex items-center gap-3 mt-3">
        <form method="POST" action="{{ route('profile.picture.update') }}" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <label class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition cursor-pointer">
                {{ $profile->profile_picture ? 'Change Picture' : 'Upload Picture' }}
                <input type="file" name="profile_picture" accept="image/*" class="hidden"
                    onchange="this.form.submit()">
            </label>
        </form>

        @if ($profile->profile_picture)
            <form method="POST" action="{{ route('profile.picture.destroy') }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="text-sm font-medium text-red-500 hover:text-red-700 transition cursor-pointer">
                    Remove
                </button>
            </form>
        @endif
    </div>
</div>

@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-8rem)] px-4 py-8">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow p-8">

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-6">
            <div class="flex justify-center mb-4">
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
            <h2 class="text-2xl font-bold">{{ $profile->first_name }} {{ $profile->last_name }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ Auth::user()->username }}</p>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-500">School</span>
                <span class="text-sm text-gray-900">{{ $profile->school }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-500">Major</span>
                <span class="text-sm text-gray-900">{{ $profile->major }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-500">Graduation Year</span>
                <span class="text-sm text-gray-900">{{ $profile->grad_year }}</span>
            </div>

            @if ($profile->about)
                <div class="pt-1">
                    <span class="text-sm font-medium text-gray-500 block mb-2">About Me</span>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $profile->about }}</p>
                </div>
            @endif
        </div>

        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Education</h3>
                @if ($profile->educationEntries->count() < 3)
                    <a href="{{ route('education.create') }}"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                        + Add Education
                    </a>
                @endif
            </div>

            @if ($profile->educationEntries->isEmpty())
                <p class="text-sm text-gray-400">No education entries yet.</p>
            @else
                <div class="space-y-4">
                    @foreach ($profile->educationEntries as $entry)
                        <div class="border border-gray-100 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $entry->degree }}</p>
                                    <p class="text-sm text-gray-600">{{ $entry->school }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $entry->start_year }} &ndash; {{ $entry->end_year }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('education.edit', $entry) }}"
                                        class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition">Edit</a>
                                    <form method="POST" action="{{ route('education.destroy', $entry) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-xs font-medium text-red-500 hover:text-red-700 transition cursor-pointer">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Experience</h3>
                @if ($profile->experienceEntries->count() < 3)
                    <a href="{{ route('experience.create') }}"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                        + Add Experience
                    </a>
                @endif
            </div>

            @if ($profile->experienceEntries->isEmpty())
                <p class="text-sm text-gray-400">No experience entries yet.</p>
            @else
                <div class="space-y-4">
                    @foreach ($profile->experienceEntries as $exp)
                        <div class="border border-gray-100 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $exp->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $exp->company }}</p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ DateTime::createFromFormat('!m', $exp->start_month)->format('M') }} {{ $exp->start_year }}
                                        &ndash;
                                        @if ($exp->end_month && $exp->end_year)
                                            {{ DateTime::createFromFormat('!m', $exp->end_month)->format('M') }} {{ $exp->end_year }}
                                        @else
                                            Present
                                        @endif
                                    </p>
                                    @if ($exp->description)
                                        <p class="text-sm text-gray-500 mt-2">{{ $exp->description }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('experience.edit', $exp) }}"
                                        class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition">Edit</a>
                                    <form method="POST" action="{{ route('experience.destroy', $exp) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-xs font-medium text-red-500 hover:text-red-700 transition cursor-pointer">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <a href="{{ route('profile.edit') }}"
            class="block text-center w-full bg-indigo-600 text-white rounded-lg py-2 font-semibold hover:bg-indigo-700 transition mt-6">
            Edit Profile
        </a>
    </div>
</div>
@if (session('success') && str_contains(session('success'), 'being processed'))
<script>
    (function () {
        const statusUrl = "{{ route('profile.picture.status') }}";
        const maxAttempts = 15;
        let attempts = 0;

        const poll = setInterval(async () => {
            attempts++;
            try {
                const res = await fetch(statusUrl);
                const data = await res.json();
                if (data.profile_picture) {
                    clearInterval(poll);
                    window.location.reload();
                }
            } catch (e) {}
            if (attempts >= maxAttempts) clearInterval(poll);
        }, 2000);
    })();
</script>
@endif
@endsection
